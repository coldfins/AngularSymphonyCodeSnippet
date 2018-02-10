'use strict';

/* Filters */
// need load the moment.js to use this filter. 
angular.module('app.filters', [])
        .filter('fromNow', function () {
            return function (date) {
                if (typeof (date) === 'string') {
                    date = parseInt(date);
                }
                return moment(date).fromNow();
            };
        })
        .filter('activeFromNow', function () {
            return function (date) {
                if (typeof (date) === 'string') {
                    date = parseInt(date);
                }

                var today = moment().diff(date, 'days') === 0 ? true : false;
                if (today) {
                    return 'Active today.'
                }

                var thisWeek = moment(new Date(), "YYYYMMDD").isSame(date, "week");
                if (thisWeek) {
                    return 'Active this week.';
                }

                var thisMonth = moment(new Date(), "YYYYMMDD").isSame(date, "month");
                if (thisMonth) {
                    return 'Active this month.';
                }

                var thisYear = moment(new Date(), "YYYYMMDD").isSame(date, "year");
                if (thisYear) {
                    return 'Active this year.';
                }

                return "";

            };
        })
        .filter('capitalize', function () {
            return function (input, all) {
                return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function (txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                }) : '';
            };
        })
        .filter('tolowercase', function () {
            return function (input) {
                return input.toLowerCase();
            };
        })
        
        .filter('removeAccents', function () {
            return function (input) {
                console.log('remove accents filter');
                var accent = [
                    /[\300-\306]/g, /[\340-\346]/g, // A, a
                    /[\310-\313]/g, /[\350-\353]/g, // E, e
                    /[\314-\317]/g, /[\354-\357]/g, // I, i
                    /[\322-\330]/g, /[\362-\370]/g, // O, o
                    /[\331-\334]/g, /[\371-\374]/g, // U, u
                    /[\321]/g, /[\361]/g, // N, n
                    /[\307]/g, /[\347]/g // C, c
                ],
                        noaccent = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'N', 'n', 'C', 'c'];

                for (var i = 0; i < accent.length; i++) {
                    input = input.replace(accent[i], noaccent[i]);
                }

                return input;
            }
        })


        .filter('trustedHTML', ['$sce', function ($sce) {
                return function (text) {
                    return text ? $sce.trustAsHtml(text.replace(/\n/g, '<br/>')) : '';
                };
            }])

        .filter('htmlToPlaintext', function () {
            return function (text) {
                return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
            };
        })
        .filter('highlightText', ['$sce', function ($sce) {
            return function (text,search) {
                if (!search) {
                    return $sce.trustAsHtml(text);
                }
                if(search.constructor !== Array){
                    return $sce.trustAsHtml(text.replace(new RegExp('('+search+')', 'gi'),'<span class="highlighted">$1</span>'));
                }else if(search.constructor === Array){
                    angular.forEach(search, function (v, k) {
                        text = $sce.trustAsHtml(text.replace(new RegExp('('+v.text+')', 'gi'),'<span class="highlighted">$1</span>'));
                    });
                    return text;
                }
            };
        }])
        .filter('trustedURL', function ($sce) {
            return function (url) {
                return $sce.trustAsResourceUrl(url);
            };
        })
        .filter('num', function () {
            return function (input) {
                return parseInt(input, 10);
            }
        })

        .filter('toArray', function () {
            //'use strict';

            return function (obj) {
                if (!(obj instanceof Object)) {
                    return obj;
                }

                return Object.keys(obj).map(function (key) {
                    return Object.defineProperty(obj[key], '$key', {__proto__: null, value: key});
                });
            }
        })

        .filter('contains', function () {
            return function (array, needle) {
                return array.indexOf(needle) >= 0;
            };
        })

        .filter('containsObject', function () {
            return function (array, needle, prop) {
                if (array.findIndex(x => x[prop] == needle) >= 0) {
                    return true;
                } else if (array.findIndex(x => x[prop] == needle.toLowerCase()) >= 0) {
                    return true
                } else {
                    return false;
                }
            };
        })
        
        .filter('myAnswers', function () {
            return function (answers, id) {
                var ans = [];
                angular.forEach(answers, function (answer) {
                    if (answer['answeredBy']['id'] === id) {
                        ans.push(answer);
                    }
                });
                return ans;
            };
        })

        .filter('today', function () {
            return function (object) {
                var filtered_list = [];
                var keys = Object.keys(object);

                for (var i = 0; i < keys.length; i++) {

                    var one_day_ago = new Date().getTime() - 1 * 24 * 60 * 60 * 1000;
                    var last_modified = new Date(object[keys[i]].date * 1000).getTime();

                    if (one_day_ago <= last_modified) {
                        filtered_list.push(object[keys[i]]);
                    }
                }
                return filtered_list;
            }
        })


        .filter('week', function () {
            return function (object) {
                var filtered_list = [];
                var keys = Object.keys(object);

                for (var i = 0; i < keys.length; i++) {

                    var now = new Date().getTime();
                    var one_day_ago = now - 1 * 24 * 60 * 60 * 1000;
                    var one_week_ago = now - 7 * 24 * 60 * 60 * 1000;
                    var last_modified = new Date(object[keys[i]].date * 1000).getTime();

                    if (last_modified > one_week_ago && last_modified < one_day_ago) {
                        filtered_list.push(object[keys[i]]);
                    }
                }
                return filtered_list;
            }
        })

        .filter('past', function () {
            return function (object) {
                var filtered_list = [];
                var keys = Object.keys(object);

                for (var i = 0; i < keys.length; i++) {

                    var now = new Date().getTime();
                    var one_day_ago = now - 1 * 24 * 60 * 60 * 1000;
                    var one_week_ago = now - 7 * 24 * 60 * 60 * 1000;
                    var last_modified = new Date(object[keys[i]].date * 1000).getTime();

                    if (last_modified < one_week_ago) {
                        filtered_list.push(object[keys[i]]);
                    }
                }
                return filtered_list;
            }
        })

        .filter('offset', function () {
            return function (input, start) {
                if (angular.isUndefined(input) || angular.isUndefined(input.length))
                    return null;

                start = parseInt(start, 10);
                return input.slice(start);
            };
        })

        .filter('unique', function () {
            return function (collection, keyname) {
                var output = [],
                        keys = [];

                angular.forEach(collection, function (item) {
                    var key = item[keyname];
                    if (keys.indexOf(key) === -1) {
                        keys.push(key);
                        output.push(item);
                    }
                });

                return output;
            }
        })

        .filter('youtubeEmbedUrl', function ($sce) {
            return function (videoId) {
                return $sce.trustAsResourceUrl('https://www.youtube.com/embed/' + videoId);
            };
        })

        .filter('vimeoEmbedUrl', function ($sce) {
            return function (videoId) {
                return $sce.trustAsResourceUrl('https://player.vimeo.com/video/' + videoId);
            };
        })

        .filter('nl2br', ['$sanitize', function ($sanitize) {
                var tag = (/xhtml/i).test(document.doctype) ? '<br />' : '<br>';
                return function (msg) {
                    // ngSanitize's linky filter changes \r and \n to &#10; and &#13; respectively
                    msg = (msg + '').replace(/(\r\n|\n\r|\r|\n|&#10;&#13;|&#13;&#10;|&#10;|&#13;)/g, tag + '$1');
                    return $sanitize(msg);
                };
            }])

        .filter('orFilter', [
            function () {
                var keyParser = function (obj) {
                    var keyList = [];
                    if (angular.isObject(obj) && !angular.isArray(obj)) {
                        for (var i in obj)
                            keyList.push(i);
                        return keyList;
                    } else {
                        return obj;
                    }
                },
                        equals = function (obj1, obj2, type) {
                            if (type)
                                return obj1 === obj2;
                            else {
                                if (angular.isString(obj1) && angular.isString(obj2))
                                    return obj1.toLocaleLowerCase() == obj2.toLocaleLowerCase();
                                else
                                    return obj1 == obj2;
                            }
                        },
                        isEmpty = function (obj) {
                            if (obj == null)
                                return true;
                            for (var name in obj) {
                                return false;
                            }
                            return true;
                        }

                return function (array, expression, comparator) {
                    var keys = keyParser(expression),
                            returnList = [],
                            keyIsArray = angular.isArray(keys);
                    comparator = comparator || false;

                    if (isEmpty(expression)) {
                        returnList = array;
                    } else {
                        for (var ary in array) {
                            if (keyIsArray && !angular.isArray(expression)) {
                                for (var key in keys) {
                                    if (equals(array[ary][keys[key]], expression[keys[key]], comparator)) {
                                        returnList.push(array[ary]);
                                    }
                                }
                            } else if (keyIsArray) {
                                for (var key in keys) {
                                    if (equals(keys[key], array[ary], comparator)) {
                                        returnList.push(array[ary]);
                                    }
                                }
                            } else {
                                if (equals(array[ary], expression, comparator)) {
                                    returnList.push(array[ary]);
                                }
                            }
                        }
                    }

                    return returnList;
                }
            }
        ])
        .filter('reverse', function () {
            return function (items) {
                return items.slice().reverse();
            };
        })
        .filter('dateDiff', function () {
            return function (startDate, endDate) {
                var sDate = moment(startDate);
                var eDate = moment(endDate);
                var diff = moment.duration(eDate.diff(sDate));
                return diff._data;
            };
        })
        .filter('slice', function () {
            return function (arr, start, end) {
                return (arr || []).slice(start, end);
            };
        })
        .filter('subStr', function () {
            return function (str, start, end, ellipsis) {
                if (ellipsis === true && str.length > end)
                    return str.substr(start, end) + '...';
                else
                    return str.substr(start, end);
            };
        })
        .filter('locations', function () {
            return function (address_components) {
                var result = {
                    'city': null,
                    'country': null,
                    'basedCountry': null
                };
                angular.forEach(address_components, function (value, key) {
                    if (value.types[0] === 'locality') {
                        result.city = value.long_name;
                    } else if (value.types[0] === 'country') {
                        result.country = value.long_name;
                        result.basedCountry = value.short_name;
                    }
                });
                return result;
            };
        })
        .filter('groupBy', function () {

            return function (list, group_by) {

                var filtered = [];
                var prev_item = null;
                var group_changed = false;
                // this is a new field which is added to each item where we append "_CHANGED"
                // to indicate a field change in the list
                var new_field = group_by + '_CHANGED';

                // loop through each item in the list
                angular.forEach(list, function (item) {

                    group_changed = false;

                    // if not the first item
                    if (prev_item !== null) {

                        // check if the group by field changed
                        if (prev_item[group_by] !== item[group_by]) {
                            group_changed = true;
                        }

                        // otherwise we have the first item in the list which is new
                    } else {
                        group_changed = true;
                    }

                    // if the group changed, then add a new field to the item
                    // to indicate this
                    if (group_changed) {
                        item[new_field] = true;
                    } else {
                        item[new_field] = false;
                    }

                    filtered.push(item);
                    prev_item = item;

                });

                return filtered;
            };
        })
        .filter('isEmpty', function () {
            var bar;
            return function (obj) {
                for (bar in obj) {
                    if (obj.hasOwnProperty(bar)) {
                        return false;
                    }
                }
                return true;
            };
        }).filter('strReplace', function () {
            return function (input, from, to) {
              input = input || '';
              from = from || '';
              to = to || '';
              return input.replace(new RegExp(from, 'g'), to);
            };
        });
