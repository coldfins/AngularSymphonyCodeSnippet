<?php

namespace Mobntouch\APIBundle\Classes;

class CSVReader {

    public $pos = 0;
    public $csv;
    public $lines = array();
    public $n;
    public $delim = ',';
    public $enable_curl_session = false;
    // Must be full name of the field
    // Must be in small-letters (Non-Capital letters)
    public $name_attributes = array(
        array('name'),
        array('first name', 'middle name', 'last name'),
        array('first', 'middle', 'last'),
        array('firstname', 'middlename', 'lastname'),
        // French
        array('nom complet'),
        array('prénom', 'nom de famille'),
        // Polish
        array('nazwa', 'nazwisko'),
        // German
        array('vorname', 'nachname'),
        // Spanish
        array('nombre', 'segundo nome', 'apellido'),
        array('apodo'),
        // Italian
        array('nome', 'secondo nome', 'cognome'),
        array('Soprannome'),
        // common
        array('maidenname'),
        array('nickname'),
        array('short name'),
        array('maiden name'),
        array('alias'),
        array('contact name'),
        array('given name', 'additional name', 'family name'),
    );
    // Must be in small-letters (Non-Capital letters)
    public $email_attributes = array(
        'email', 'e-mail', 'adresse', 'address', 'location', 'notes',
        'correo', 'posta', 'elettronica'//, 'Dirección de correo electrónico'
    );
    // Must be in small-letters (Non-Capital letters)
    public $jobtitle_attributes = array(
        'job title', 'Titre de l\'emploi', 'Stanowisko', 'Berufsbezeichnung', 'Título profesional', 'Titolo di lavoro'
    );
    // Must be in small-letters (Non-Capital letters)
    public $company_attributes = array(
        'work_company', 'company'
    );
    // Must be partial text to find in the field name
    // Must be in small-letters (Non-Capital letters)
    public $supported_formats = array('csv', 'vcf', 'txt', 'ldif');
    public $media_key = '';
    public $columns = array();
    public $name_indices = array();
    public $email_indices = array();
    public $company_indices = array();
    public $jobtitle_indices = array();
    public $valid_cf_file = false;
    // Error Handling
    public $internal_error = '';

    function set_contents($csv, $delim = ',') {
        $this->csv = $csv;
        $this->n = strlen($csv);
        $this->delim = $delim;
        $this->pos = 0;
    }

    function nextRow($case = false) {
        $cells = array();
        $addCount = 0;
        $n = strlen($this->csv);
        $i = $this->pos;
        while (true) {
            $sb = '';
            $inQuote = false;
            $eol = false;
            $quoteAllowed = true;
            $lastChar = '';
            $hasData = false;
            while (true) {
                if ($i >= $n) {
                    $eol = true;
                    break;
                }
                $c = $this->csv[$i++];
                $z = ord($c);
                // if($z != 0 && $z != 254 && $z != 255)
                {
                    $hasData = true;
                    if ($lastChar === '"' && $c !== '"' && $inQuote) {
                        $inQuote = false;
                    }
                    if ($c === $this->delim) {
                        if ($inQuote) {
                            if ($lastChar === '"')
                                break;
                            else
                                $sb.=$c;
                        } else {
                            $lastChar = $c;
                            break;
                        }
                    } else if ($c === '"') {
                        if ($inQuote) {
                            if ($lastChar === '"') {
                                $sb.=$c;
                                $c = '';
                            }
                        } else {
                            if ($quoteAllowed) {
                                $inQuote = true;
                                $c = '';
                            } else {
                                $sb.=$c;
                            }
                        }
                    } else if ($c === "\r") {
                        if ($inQuote)
                            $sb.=$c;
                    }
                    else if ($c === "\n") {
                        if ($inQuote) {
                            $sb.=$c;
                        } else {
                            $eol = true;
                            break;
                        }
                    } else {
                        $sb.=$c;
                        $quoteAllowed = false;
                    }
                    $lastChar = $c;
                }
            }
            $this->pos = $i;
            if (!$hasData)
                return null;
            $cells[] = ($case) ? strtolower(trim($sb, ' "')) : trim($sb, ' "');
            if ($eol)
                return $cells;
        }
    }

    final function get_contacts_from_file($contents = '', $format = 'csv', $delimiter = ',') {
        if ($result = $this->set_contact_file_contents($contents, $format, $delimiter)) {
            if ($this->valid_cf_file) {
                //$this->reset_channel();
                //print_r($this->name_indices);
                if ($format == 'csv') {
                    $this->reset_channel_1(str_ireplace(array("\r", "\n", '\r', '\n'), '::-::', $contents), $this->name_indices, $this->email_indices, $this->company_indices);
                } else {
                    $this->reset_channel();
                }
            }
        }
        return $this->contacts;
    }

    function set_contact_file_contents($contents = '', $format = 'csv', $delimiter = ',') {
        if (empty($contents) || !is_string($contents)) {
            $this->internal_error = 'Contact file is empty.';
            return ($this->valid_cf_file = false);
        }
        $format = strtolower($format);
        if (!in_array($format, $this->supported_formats)) {
            $this->internal_error = 'Invalid file format.';
            return ($this->valid_cf_file = false);
        }

        $contents = $this->parse_encoding($contents);
        $this->media_key = $format . '_contacts';
        $this->set_contents($contents, $delimiter);
        $this->contacts = array();

        if (!empty($contents)) {
            $this->valid_cf_file = true;
            $extracter = 'get_contacts_from_' . $format;
            if (method_exists($this, $extracter)) {
                $result = $this->$extracter($contents, $delimiter);
            }
            return true;
        }
        return false;
    }

    function parse_encoding($contents = '') {
        $line_separator = "\r\n";
        $current_encoding = $base_encoding = '';
        $msb_offset = 1;
        $lsb_offset = 0;
        if (strpos($contents, "\xef\xbb\xbf") !== false) { // UTF-8
            $contents = substr($contents, 3);
            $line_separator = "\x0d\x0a";
            if (strpos($contents, "\x0d\x0a") !== false) {
                $line_separator = "\x0d\x0a";
            } else if (strpos($contents, "\x0a") !== false) {
                $line_separator = "\x0a";
            } else if (strpos($contents, "\x0d") !== false) {
                $line_separator = "\x0d";
            }
            $current_encoding = 'utf8';
        } else if (strpos($contents, "\xff\xfe") !== false) { // UTF-16 LE
            $contents = substr($contents, 2);
            $line_separator = "\x0d\x00\x0a\x00";
            if (strpos($contents, "\x0d\x00\x0a\x00") !== false) {
                $line_separator = "\x0d\x00\x0a\x00";
            } else if (strpos($contents, "\x0a\x00") !== false) {
                $line_separator = "\x0a\x00";
            } else if (strpos($contents, "\x0d\x00") !== false) {
                $line_separator = "\x0d\x00";
            }
            $current_encoding = 'utf16';
            $base_encoding = 'UTF-16LE';
        } else if (strpos($contents, "\xfe\xff") !== false) { // UTF-16 BE
            $contents = substr($contents, 2);
            $line_separator = "\x00\x0d\x00\x0a";
            if (strpos($contents, "\x00\x0d\x00\x0a") !== false) {
                $line_separator = "\x00\x0d\x00\x0a";
            } else if (strpos($contents, "\x00\x0a") !== false) {
                $line_separator = "\x00\x0a";
            } else if (strpos($contents, "\x00\x0d") !== false) {
                $line_separator = "\x00\x0d";
            }
            $current_encoding = 'utf16';
            $base_encoding = 'UTF-16BE';
            $msb_offset = 0;
            $lsb_offset = 1;
        } else {
            if (strpos($contents, "\r\n") !== false) {
                $line_separator = "\r\n";
            } else if (strpos($contents, "\n") !== false) {
                $line_separator = "\n";
            } else if (strpos($contents, "\r") !== false) {
                $line_separator = "\r";
            }
        }

        if ($current_encoding == 'utf16') {
            $lines = explode($line_separator, $contents);
            $ln_cnt = 0;
            foreach ($lines as $ind => $ln) {
                $nln = '';
                $len = strlen($ln);
                for ($i = 0; $i < $len; $i+=2) {
                    if (ord($ln[$i + $msb_offset]) > 0) {
                        $nln .= '&#x' . bin2hex($ln[$i + $msb_offset]) . bin2hex($ln[$i + $lsb_offset]) . ';';
                    } else {
                        if (ord($ln[$i + $lsb_offset]) > 127) {
                            $nln .= '&#x00' . bin2hex($ln[$i + $lsb_offset]) . ';';
                        } else {
                            $nln .= $ln[$i + $lsb_offset];
                        }
                    }
                }
                $lines[$ind] = $nln;
            }
            $contents = implode("\r\n", $lines);
        } else if ($line_separator != "\r\n") {
            $contents = str_replace($line_separator, "\r\n", $contents);
        }

        if (preg_match('/[^\r\n][\r\n]{2}[^\r\n]/', $contents, $matches) == 0) {
            $contents = preg_replace('/([\r\n]{2}){2}/i', '$1', $contents);
        }
        return $contents;
    }

// Parsing functions for CSV format
    function get_contacts_from_csv($contents = '', $delimiter = ',') {
        $this->columns = $this->nextRow(true);
        $email_regex = '/[a-z0-9\.\-_]+@[a-z0-9\-_]+\.[a-z0-9\.]+/i';
        if (count($this->columns) <= 1) {
            $this->set_contents($contents, ';');
            $this->columns = $this->nextRow(true);
        }
        if (count($this->columns) > 1) {

            $this->get_indices($this->columns);
        }
        if (count($this->name_indices) == 0) {
            $this->internal_error = 'Name Field not found.';
        }
        if (count($this->email_indices) == 0) {
            $this->internal_error = 'Email field not found.';
        }

        return true;
    }

    function get_indices($cells) {
        if (count($cells) > 2) {
            $cells = array_map("strtolower", $cells);
            foreach ($this->name_attributes as $group_id => $fields) {
                $result = array_intersect($cells, $fields);
                if (count($result) > 0) {
                    foreach ($result as $index => $field) {
                        $this->name_indices[$group_id][$field] = $index;
                    }
                }
            }
            foreach ($cells as $index => $attr_name) {
                foreach ($this->email_attributes as $email_attr_name) {
                    if (strpos($attr_name, $email_attr_name) !== false) {
                        $this->email_indices[$index] = $attr_name;
                    }
                }

                foreach ($this->company_attributes as $company_attr_name) {
                    if (strpos($attr_name, $company_attr_name) !== false) {
                        $this->company_indices[$index] = $attr_name;
                    }
                }

                foreach ($this->jobtitle_attributes as $jobtitle_attribute) {
                    if (strpos($attr_name, $jobtitle_attribute) !== false) {
                        $this->jobtitle_indices[$index] = $attr_name;
                    }
                }
            }
        } else if (count($cells) == 2) {
            $this->name_indices = array(1 => array('default' => 0));
            $this->email_indices = array(1 => array(1));
            $this->company_indices = array(1 => array(1));
            $this->jobtitle_indices = array(1 => array(1));
        } else if (count($cells) == 1) {
            $this->name_indices = array(1 => array('default' => 3));
            $this->email_indices = array(1 => array(1));
            $this->company_indices = array(1 => array(1));
            $this->jobtitle_indices = array(1 => array(1));
        } else {
            $this->internal_error = '0 attributes found.';
            return false;
        }
    }

}
