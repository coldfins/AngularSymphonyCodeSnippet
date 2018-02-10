<?php

namespace Mobntouch\APIBundle\Controller;

# Symfony

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
# Exceptions
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;     // 404
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;   // 400
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException; // 403
# DOCUMENTS
use Mobntouch\DataBaseBundle\Document\User;
use Mobntouch\DataBaseBundle\Document\Qa;
use Mobntouch\APIBundle\Classes\Utility;

# EXTRA HELPER CLASSES

class QaController extends FOSRestController {

    public function addQuestionAction(Request $request) {
        $user = $this->getCurrentUser(); //Get current loggedin user
        if (!$user instanceof User) {
            throw new NotFoundHttpException();     // 404
        }

        $data = (object) $request->request->all();

        if (!isset($data->title) || !isset($data->tags)) {
            throw new NotFoundHttpException();     // 404
        }
        $search = array(); // search to filter question based on title tags and description
        $title = $data->title;
        $search[] = $title;
        $details = isset($data->details) ? $data->details : null;
        if ($details) {
            $search[] = $details;
        }
        $tags = $data->tags;
        $this->imitateMerge($search, $tags);
        $isAnonymously = isset($data->isAnonymously) ? $data->isAnonymously : false;
        $sendNotification = isset($data->sendNotification) ? $data->sendNotification : false;
        $slug = $this->cleanSlug($this->removeAccents($title));
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        for ($i = 1;;) {
            $exists = $dm->getRepository('DataBaseBundle:Qa')->findOneBy(array('slug' => $slug));
            if (!$exists) {
                break;
            } else {
                $slug = $this->cleanSlug($this->removeAccents($title)) . '-' . $i;
            }
            $i++;
        }

        $query = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->insert()
                ->field("title")->set($title)
                ->field("slug")->set($slug)
                ->field("details")->set($details)
                ->field("tags")->set($tags)
                ->field("isAnonymously")->set($isAnonymously)
                ->field("sendNotification")->set($sendNotification)
                ->field("askedBy")->set(array(
                    'id' => $user->getId(),
                    'username' => $isAnonymously ? null : $user->getUsername(),
                    'name' => $isAnonymously ? 'Anonymous' : $user->getName(),
                    'lastname' => $isAnonymously ? '' : $user->getLastname(),
                    'avatar' => $isAnonymously ? null : $user->getAvatar(),
                    'cover' => $isAnonymously ? null : $user->getCover(),
                    'jobTitle' => $isAnonymously ? null : $user->getJobTitle(),
                    'company' => $isAnonymously ? null : $user->getCompany()
                ))
                ->field("answers")->set(array())
                ->field("savedBy")->set(array())
                ->field("upVotes")->set(0)
                ->field("downVotes")->set(0)
                ->field("pageViews")->set(0)
                ->field("search")->set($search)
                ->field("createDate")->set(time())
                ->field("updateDate")->set(time())
                ->getQuery();
        $query->execute();
        $qa = $query->getQuery()['newObj'];
        $qa['id'] = $qa['_id']->{'$id'};

        return new JsonResponse(array('question' => $qa));
    }

    public function removeQuestionAction(Request $request) {
        $user = $this->getCurrentUser(); //Get current loggedin user
        if (!$user instanceof User) {
            throw new NotFoundHttpException();     // 404
        }

        $data = (object) $request->request->all();

        if (!isset($data->questionId)) {
            throw new NotFoundHttpException();     // 404
        }

        $dm = $this->get('doctrine_mongodb.odm.document_manager');

        $question = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->field('_id')->equals($data->questionId)
                ->field("askedBy.id")->equals($user->getId())
                ->upsert(false)
                ->getQuery()
                ->getSingleResult();

        if (!$question) {
            throw new NotFoundHttpException();
        }

        $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->remove()
                ->field('_id')->equals($data->questionId)
                ->field("askedBy.id")->equals($user->getId())
                ->getQuery()
                ->execute();

        $dm->flush();
        $dm->clear();

        return new JsonResponse(array('removed' => true));
    }

    public function updateQuestionAction(Request $request) {
        $user = $this->getCurrentUser(); //Get current loggedin user
        if (!$user instanceof User) {
            throw new NotFoundHttpException();     // 404
        }

        $data = (object) $request->request->all();

        if (!isset($data->id) || !isset($data->title) || !isset($data->tags)) {
            throw new NotFoundHttpException();     // 404
        }

        $search = array(); // search to filter question based on title tags and description
        $title = $data->title;
        $search[] = $title;
        $details = isset($data->details) ? $data->details : null;
        if ($details) {
            $search[] = $details;
        }
        $tags = $data->tags;
        $this->imitateMerge($search, $tags);
        $isAnonymously = isset($data->isAnonymously) ? $data->isAnonymously : false;
        $sendNotification = isset($data->sendNotification) ? $data->sendNotification : false;
        $dm = $this->get('doctrine_mongodb.odm.document_manager');

        $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->update()
                ->multiple(false)
                ->field("_id")->equals($data->id)
                ->field("askedBy.id")->equals($user->getId())
                ->field("title")->set($title)
                ->field("details")->set($details)
                ->field("tags")->set($tags)
                ->field("isAnonymously")->set($isAnonymously)
                ->field("sendNotification")->set($sendNotification)
                ->field("askedBy")->set(array(
                    'id' => $user->getId(),
                    'username' => $isAnonymously ? null : $user->getUsername(),
                    'name' => $isAnonymously ? 'Anonymous' : $user->getName(),
                    'lastname' => $isAnonymously ? '' : $user->getLastname(),
                    'avatar' => $isAnonymously ? null : $user->getAvatar(),
                    'cover' => $isAnonymously ? null : $user->getCover(),
                    'jobTitle' => $isAnonymously ? null : $user->getJobTitle(),
                    'company' => $isAnonymously ? null : $user->getCompany()
                ))
                ->field("search")->set($search)
                ->field("updateDate")->set(time())
                ->getQuery()
                ->execute();

        return new JsonResponse(array());
    }

    public function getQuestionsAction(Request $request) {
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $raw_token = $request->headers->get('authorization');
        $user = null;
        if (isset($raw_token)) {
            $token = substr($raw_token, strpos($raw_token, '.') + 1);
            $uData = json_decode(base64_decode(substr($token, 0, strpos($token, '.'))));
            $user = $dm->getRepository('DataBaseBundle:User')->findOneBy(array('username' => $uData->username));
        }

        if ($user && !$user->getHasVisitedQA()) {
            $user->setHasVisitedQA(true);
            $dm->flush();
            $dm->clear();
        }

        $data = (object) $request->request->all();
        $limit = 20;
        $skip = 0;
        if (isset($data->skip)) {
            $skip = $data->skip;
        }

        $arrTags = array();
        if (isset($data->query) && is_array($data->query)) {
            foreach ($data->query as $query) {
                $texts = explode(' ', $query['text']);
                $textsCount = count($texts);
                if ($textsCount > 1) {
                    foreach ($texts as $text) {
                        $text = str_replace('#', '', $text);
                        $arrTags[] = new \MongoRegex("/{$text}/i");
                    }
                } else {
                    $text = str_replace('#', '', $texts[0]);
                    $arrTags[] = new \MongoRegex("/{$text}/i");
                }
            }
        }

        $query = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->select('id', 'title', 'slug', 'details', 'askedBy', 'tags', 'isAnonymously', 'answers', 'pageViews', 'upVotes', 'downVotes', 'createDate', 'updateDate')
                ->hydrate(false);

        if (isset($data->qa) && $data->qa == 'latest') {
            $query->sort('createDate', -1);
        } else if (isset($data->qa) && $data->qa == 'trending') {
            $query->sort('pageViews', -1);
        } else if ($user && isset($data->qa) && $data->qa == 'questions') {
            $query->field('askedBy.id')->equals($user->getId());
            $query->sort('createDate', -1);
        } else if ($user && isset($data->qa) && $data->qa == 'answers') {
            $query->field('answers.answeredBy.id')->equals($user->getId());
            $query->sort('createDate', -1);
        } else if ($user && isset($data->qa) && $data->qa == 'saved') {
            $query->field('savedBy.id')->equals($user->getId());
            $query->sort('createDate', -1);
        }

        if ($arrTags) {
            //$query->field('tags')->all($arrTags); //For tags only
            /* $query->addOr(
              $query->expr()->field('tags')->in($arrTags)
              )
              ->addOr(
              $query->expr()->field('title')->in($arrTags)
              )
              ->addOr(
              $query->expr()->field('details')->in($arrTags)
              ); */
            $query->field('search')->all($arrTags);
        }

        $count = $query->getQuery()->execute()->count();
        $questions = $query
                ->limit($limit)
                ->skip($skip * $limit)
                ->getQuery()
                ->execute();
        $arrQuestions = array();
        foreach ($questions as $question) {
            $this->getSortedQuestion($question, $user);
            $question['mostVotedAns'] = null;
            if (count($question['answers']) > 0) {
                $question['mostVotedAns'] = $question['answers'][0];
                $question['mostVotedAnsIndex'] = $question['answers'][0]['ansIndex'];
            }

            //Older mostvoted ans code refactored in sortquestion e.g above code
            //$this->getMostVotedAns($question);
            /* if ($question['mostVotedAns'] && array_key_exists('upVotedBy', $question['mostVotedAns'])) {
              unset($question['mostVotedAns']['upVotedBy']);
              }
              if ($question['mostVotedAns'] && array_key_exists('downVotedBy', $question['mostVotedAns'])) {
              unset($question['mostVotedAns']['downVotedBy']);
              } */
            $question['myAns'] = null;
            $question['myAnsCount'] = 0;
            if ($user) {
                $this->getMyAns($question, $user);
            }
            //$question['id'] = $question['_id']->{'$id'};

            $question['answersCount'] = count($question['answers']);
            unset($question['answers']);
            unset($question['_id']);
            $arrQuestions[] = $question;
        }
        $qStats = $this->questionsCounter($dm, $user);
        return new JsonResponse(array('user' => $user, 'questions' => $arrQuestions, 'count' => $count, 'myQuestions' => $qStats['myQuestions'], 'myAnswers' => $qStats['myAnswers'], 'saved' => $qStats['saved']));
    }

    public function getQuestionsStatsAction(Request $request) {

        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $raw_token = $request->headers->get('authorization');
        $user = null;
        if (isset($raw_token)) {
            $token = substr($raw_token, strpos($raw_token, '.') + 1);
            $uData = json_decode(base64_decode(substr($token, 0, strpos($token, '.'))));
            $user = $dm->getRepository('DataBaseBundle:User')->findOneBy(array('username' => $uData->username));
        }

        $qStats = $this->questionsCounter($dm, $user);
        return new JsonResponse(array('myQuestions' => $qStats['myQuestions'], 'myAnswers' => $qStats['myAnswers'], 'saved' => $qStats['saved']));
    }

    public function getQuestionBySlugAction(Request $request, $slug) {
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $raw_token = $request->headers->get('authorization');
        $user = null;
        if (isset($raw_token)) {
            $token = substr($raw_token, strpos($raw_token, '.') + 1);
            $uData = json_decode(base64_decode(substr($token, 0, strpos($token, '.'))));
            $user = $dm->getRepository('DataBaseBundle:User')->findOneBy(array('username' => $uData->username));
        }

        //$qa = $dm->getRepository('DataBaseBundle:Qa')->findOneBy(array('slug' => $slug));
        $qa = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->hydrate(false)
                ->select('id', 'title', 'slug', 'details', 'askedBy', 'tags', 'isAnonymously','sendNotification', 'answers', 'pageViews', 'upVotes', 'downVotes', 'createDate', 'updateDate')
                ->field('slug')->equals($slug)
                ->getQuery()
                ->getSingleResult();

        if (!$qa) {
            throw new NotFoundHttpException();     // 404
        }

        $this->getSortedQuestion($qa, $user);

        $savedBy = isset($qa['savedBy']) ? $qa['savedBy'] : array();
        $day = date("m-d-y H:i:s");
        $time = "history.$day.time";
        $views = "history.$day.views";
        $uniqueViews = "history.$day.uniqueViews";
        if (isset($user) && $user) {
            if (is_array($savedBy)) {
                $savedIds = $this->array_column($savedBy, 'id');
                $qa['isSaved'] = in_array($user->getId(), $savedIds);
            }

            $dm->createQueryBuilder('DataBaseBundle:Qa')
                    ->update()
                    ->multiple(false)
                    ->field('_id')->equals($qa['id'])
                    ->field($time)->set(time())
                    ->field($views)->inc(intval(1))
                    ->field($uniqueViews)->addToSet($user ? array('userId' => $user->getId(), 'type' => 'user') : array('userId' => null, 'type' => 'anonymous'))
                    ->field("pageViews")->inc(intval(1))
                    ->field("uniquePageViews")->addToSet($user ? array('userId' => $user->getId(), 'type' => 'user') : array('userId' => null, 'type' => 'anonymous'))
                    ->field("dailyUniquePageViews")->addToSet(array('date' => $day))
                    ->field("updateDate")->set(time())
                    ->upsert(false)
                    ->getQuery()
                    ->execute();
        } else {
            $dm->createQueryBuilder('DataBaseBundle:Qa')
                    ->update()
                    ->multiple(false)
                    ->field('_id')->equals($qa['id'])
                    ->field($time)->set(time())
                    ->field($views)->inc(intval(1))
                    ->field("pageViews")->inc(intval(1))
                    ->field("dailyUniquePageViews")->addToSet(array('date' => $day))
                    ->field("updateDate")->set(time())
                    ->upsert(false)
                    ->getQuery()
                    ->execute();
        }

        $dm->flush();
        $dm->clear();

        $qStats = $this->questionsCounter($dm, $user);
        $arrJobs = $this->getNearByJobs($dm, $user);
        $qa['pageViews'] = $qa['pageViews'] + 1;
        return new JsonResponse(array('question' => $qa, 'nearJobs' => $arrJobs, 'myQuestions' => $qStats['myQuestions'], 'myAnswers' => $qStats['myAnswers'], 'saved' => $qStats['saved']));
    }

    public function getQuestionByTagAction(Request $request, $limit, $offset, $tag) {

        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $raw_token = $request->headers->get('authorization');
        $user = null;
        if (isset($raw_token)) {
            $token = substr($raw_token, strpos($raw_token, '.') + 1);
            $uData = json_decode(base64_decode(substr($token, 0, strpos($token, '.'))));
            $user = $dm->getRepository('DataBaseBundle:User')->findOneBy(array('username' => $uData->username));
        }

        $arrTags[] = new \MongoRegex("/^{$tag}$/i");
        $query = $dm->createQueryBuilder('DataBaseBundle:Qa')
                        ->select('id', 'title', 'slug', 'details', 'askedBy', 'tags', 'isAnonymously', 'answers', 'pageViews', 'upVotes', 'downVotes', 'createDate', 'updateDate')
                        ->hydrate(false)
                        ->field('tags')->all($arrTags);
        $count = $query->getQuery()->execute()->count();
        $questions = $query
                ->sort('createDate', -1)
                ->limit($limit)
                ->skip($offset * $limit)
                ->getQuery()
                ->execute();

        $arrQuestions = array();
        foreach ($questions as $question) {
            $this->getMostVotedAns($question);
            if ($question['mostVotedAns'] && array_key_exists('upVotedBy', $question['mostVotedAns'])) {
                unset($question['mostVotedAns']['upVotedBy']);
            }
            if ($question['mostVotedAns'] && array_key_exists('downVotedBy', $question['mostVotedAns'])) {
                unset($question['mostVotedAns']['downVotedBy']);
            }
            $question['id'] = $question['_id']->{'$id'};
            $question['answersCount'] = count($question['answers']);
            unset($question['answers']);
            unset($question['_id']);
            $arrQuestions[] = $question;
        }

        $tagStats = $this->tagCounter($dm, $tag);
        $arrJobs = $this->getNearByJobs($dm, $user);
        return new JsonResponse(array('count' => $count, 'questions' => $arrQuestions, 'nearJobs' => $arrJobs, 'tagStats' => $tagStats));
    }

    public function addSaveQuestionAction(Request $request) {
        $user = $this->getCurrentUser(); //Get current loggedin user
        if (!$user instanceof User) {
            throw new NotFoundHttpException();     // 404
        }

        $data = (object) $request->request->all();

        if (!isset($data->questionId)) {
            throw new NotFoundHttpException();     // 404
        }

        $questionId = $data->questionId;

        $dm = $this->get('doctrine_mongodb.odm.document_manager');

        $question = $dm->getRepository('DataBaseBundle:Qa')->findOneBy(array('id' => $questionId));

        if (!$question) {
            throw new NotFoundHttpException();
        }

        $savedBy = $question->getSavedBy();
        $savedBy[] = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'name' => $user->getName(),
            'lastname' => $user->getLastname(),
            'jobTitle' => $user->getJobTitle(),
            'company' => $user->getCompany(),
            'avatar' => $user->getAvatar(),
            'cover' => $user->getCover(),
            'date' => time()
        );

        $question->setSavedBy($savedBy);
        $dm->flush();
        $dm->clear();
        $question->isSaved = true;

        return new JsonResponse(array('question' => $question));
    }

    public function removeSavedQuestionAction(Request $request) {
        $user = $this->getCurrentUser(); //Get current loggedin user
        if (!$user instanceof User) {
            throw new NotFoundHttpException();     // 404
        }

        $data = (object) $request->request->all();

        if (!isset($data->questionId)) {
            throw new NotFoundHttpException();     // 404
        }

        $questionId = $data->questionId;

        $dm = $this->get('doctrine_mongodb.odm.document_manager');

        $question = $dm->getRepository('DataBaseBundle:Qa')->findOneBy(array('id' => $questionId));

        if (!$question) {
            throw new NotFoundHttpException();
        }

        $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->update()
                ->multiple(false)
                ->field('_id')->equals($question->getId())
                ->field("savedBy")->pull(array('id' => $user->getId()))
                ->upsert(false)
                ->getQuery()
                ->execute();

        $dm->flush();
        $dm->clear();

        $question = $dm->getRepository('DataBaseBundle:Qa')->findOneBy(array('id' => $questionId));
        $question->isSaved = false;

        return new JsonResponse(array('question' => $question));
    }

    public function postAnswerAction(Request $request) {
        $user = $this->getCurrentUser(); //Get current loggedin user
        if (!$user instanceof User) {
            throw new NotFoundHttpException();     // 404
        }

        $data = (object) $request->request->all();

        if (!isset($data->details) || !isset($data->questionId)) {
            throw new NotFoundHttpException();     // 404
        }

        $questionId = $data->questionId;
        $details = $data->details;
        $isAnonymously = isset($data->isAnonymously) ? $data->isAnonymously : false;

        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $question = $dm->getRepository('DataBaseBundle:Qa')->findOneBy(array('id' => $questionId));

        if (!$question) {
            throw new NotFoundHttpException();
        }

        $answers = $question->getAnswers();
        $answers[] = array(
            'answer' => $details,
            'upVote' => 0,
            'upVotedBy' => array(),
            'downVote' => 0,
            'downVotedBy' => array(),
            'isAnonymously' => $isAnonymously,
            'answeredBy' => array(
                'id' => $user->getId(),
                'username' => $isAnonymously ? null : $user->getUsername(),
                'name' => $isAnonymously ? 'Anonymous' : $user->getName(),
                'lastname' => $isAnonymously ? '' : $user->getLastname(),
                'jobTitle' => $isAnonymously ? null : $user->getJobTitle(),
                'company' => $isAnonymously ? null : $user->getCompany(),
                'avatar' => $isAnonymously ? null : $user->getAvatar(),
                'cover' => $isAnonymously ? null : $user->getCover()
            ),
            'createDate' => time(),
            'updateDate' => time()
        );

        $question->setAnswers($answers);

        $aUser = $dm->getRepository('DataBaseBundle:User')->findOneBy(array('id' => $question->getAskedBy()['id']));
        if ($aUser && $aUser->getValidated() && $aUser->getId() != $user->getId()) {
            $dm->createQueryBuilder('DataBaseBundle:User')
                    ->update()
                    ->multiple(true)
                    ->field('id')->equals($question->getAskedBy()['id'])
                    ->field('alertsNotifications')->inc(1)
                    ->field('alerts')->push(array('$each' => array(array(
                                'id' => $question->getId(),
                                'type' => 15, //Has poasted an answer on your question
                                'read' => false,
                                'action' => 'has posted an answer on your question',
                                'userId' => $user->getId(),
                                'username' => $isAnonymously ? null : $user->getUsername(),
                                'name' => $isAnonymously ? 'Anonymous' : $user->getName(),
                                'lastname' => $isAnonymously ? '' : $user->getLastname(),
                                'avatar' => $isAnonymously ? null : $user->getAvatar(),
                                'slug' => $question->getSlug(),
                                'date' => time() * 1000
                            )), '$slice' => -90))
                    ->field('updateDate')->set(time())
                    ->getQuery()
                    ->execute();

            $parts = parse_url($_SERVER['HTTP_REFERER']);
            $link = 'https://' . $parts["host"];
            $question_link = 'https://' . $parts["host"] . '/question/' . $question->getSlug();
            $question_title = $question->getTitle();
            $profileLink = 'https://' . $parts["host"] . "/profile/" . $user->getUsername();
            $ans = substr(strip_tags($details), 0, 200);
            $env = $this->get('kernel')->getEnvironment();
            switch ($env) {
                case 'adhoc':
                    $baseURL = 'https://cdn-dev.mobintouch.com';
                    break;
                case 'prod':
                    $baseURL = 'https://cdn.mobintouch.com';
                    break;
                default:
                    $baseURL = 'https://cdn.mobintouch.com';
                    break;
            }
            $profilePicture = $user->getAvatar() ? $baseURL . $user->getAvatar() : $baseURL . '/img/mit-default-avatar.png';
            $settings = $aUser->getSettings();
            if (/* $env == 'prod' && */$question->getSendNotification() && isset($settings['notifications']) and isset($settings['notifications'][0]) and isset($settings['notifications'][0]['email_qa']) and $settings['notifications'][0]['email_qa']) {
                /* $send_grid_options = array(
                  'sub' => array(':logo_link' => array($link), ':logo_name_link' => array($link), ':question_link' => array($question_link), ':question_title' => array($question_title), ':sender_profile_link' => array($profileLink), ':sender_first_name' => array($user->getName()), ':sender_last_name' => array($user->getLastname()), ':sender_job_title' => array($user->getJobtitle()), ':sender_company_name' => array($user->getCompany()), ':sender_truncated_200char_answer' => array($ans), ':sender_answer_link' => array($question_link), ':sender_picture_link' => array($userProfileUrl)),
                  'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => $this->container->getParameter('template_new_ans_to_question_id'))))
                  );

                  $params = array(
                  'to' => $aUser->getEmail(),
                  'from' => "noreply@mobintouch.com",
                  'fromname' => "Mobintouch",
                  'subject' => $name . " sent you a new answer",
                  'html' => " ",
                  'x-smtpapi' => json_encode($send_grid_options),
                  );
                  Utility::sendgrid_mail($params, $this->container->getParameter('group_question_ans')); */


                $params = array(
                    'personalizations' => array(
                        array(
                            'to' => array(
                                array('email' => $aUser->getEmail())
                            ),
                            'substitutions' => array(
                                ':logo_link' => $link,
                                ':logo_name_link' => $link,
                                ':sender_first_name' => $isAnonymously ? 'Anonymous' : $user->getName(),
                                ':sender_last_name' => $isAnonymously ? 'Anonymous' : $user->getLastname(),
                                ':sender_picture_link' => $isAnonymously ? $baseURL . '/img/mit-default-avatar.png' : $profilePicture,
                                ':question_link' => $question_link,
                                ':question_title' => $question_title,
                                ':sender_profile_link' => $isAnonymously ? '#' : $profileLink,
                                ':sender_job_title' => $user->getJobTitle(),
                                ':sender_company_name' => $user->getCompany(),
                                ':sender_job_title_at_company' => $user->getJobTitle() && $user->getCompany() ? $user->getJobTitle() . ' at ' . $user->getCompany() : $user->getJobTitle(),
                                ':sender_truncated_200char_answer' => substr(strip_tags($details), 0, 200),
                                ':sender_answer_link' => $question_link
                            )
                        )
                    ),
                    'from' => array(
                        'email' => "noreply@mobintouch.com",
                        'name' => "Mobintouch"
                    ),
                    'subject' => $isAnonymously ? 'Anonymous' : $user->getName() . " sent you a new answer",
                    'content' => array(array(
                            'type' => 'text/html',
                            'value' => ' '
                        )
                    ),
                    'template_id' => $this->container->getParameter('template_new_ans_to_question_id'),
                    'asm' => array(
                        'group_id' => $this->container->getParameter('group_question_ans')
                    )
                );
                Utility::sendgrid_mail(json_encode($params));
            }

            if ($aUser->getPlayerId()) {
                $players[] = $aUser->getPlayerId();
                $parts = parse_url($_SERVER['HTTP_REFERER']);
                $link = 'https://' . $parts["host"] . "/question/" . $question->getSlug();
                $this->send_push_notification(null, $isAnonymously ? 'Anonymous' : $user->getName() . " " . $isAnonymously ? '' : $user->getLastname() . " has posted answer on your question.", $players, $link);
            }
        }

        $dm->flush();
        $dm->clear();

        $question = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->hydrate(false)
                ->select('id', 'title', 'slug', 'details', 'askedBy', 'tags', 'isAnonymously', 'answers', 'pageViews', 'upVotes', 'downVotes', 'createDate', 'updateDate')
                ->field('id')->equals($questionId)
                ->getQuery()
                ->getSingleResult();

        $this->getSortedQuestion($question, $user);

        return new JsonResponse(array('question' => $question));
    }

    public function updateAnswerAction(Request $request) {
        $user = $this->getCurrentUser(); //Get current loggedin user
        if (!$user instanceof User) {
            throw new NotFoundHttpException();     // 404
        }

        $data = (object) $request->request->all();

        if (!isset($data->questionId) || !isset($data->updatedAnswer) || !isset($data->answer)) {
            throw new NotFoundHttpException();     // 404
        }

        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $question = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->field('_id')->equals($data->questionId)
                ->field("answers.answeredBy.id")->equals($user->getId())
                ->getQuery()
                ->getSingleResult();

        if (!$question) {
            throw new NotFoundHttpException();
        }

        if (isset($data->answer['edit'])) {
            unset($data->answer['edit']);
        }

        //Refectored script
        /* if (isset($data->answer['ansIndex'])) {
          unset($data->answer['ansIndex']);
          } */

        if (isset($data->answer['connectionStatus'])) {
            unset($data->answer['connectionStatus']);
        }

        $answers = $question->getAnswers();
        $answerIndex = $data->answer['ansIndex']; //array_search($data->answer, $answers);//Refectored script


        $answer = $data->updatedAnswer['answer'];
        $isAnonymously = $data->updatedAnswer['isAnonymously'];
        $answeredBy = array(
            'id' => $user->getId(),
            'username' => $isAnonymously ? null : $user->getUsername(),
            'name' => $isAnonymously ? 'Anonymous' : $user->getName(),
            'lastname' => $isAnonymously ? '' : $user->getLastname(),
            'jobTitle' => $isAnonymously ? null : $user->getJobTitle(),
            'company' => $isAnonymously ? null : $user->getCompany(),
            'avatar' => $isAnonymously ? null : $user->getAvatar(),
            'cover' => $isAnonymously ? null : $user->getCover()
        );

        $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->update()
                ->multiple(false)
                ->field('_id')->equals($data->questionId)
                ->field("answers.$answerIndex.answeredBy.id")->equals($user->getId())
                ->field("answers.$answerIndex.answer")->set($answer)
                ->field("answers.$answerIndex.answeredBy")->set($answeredBy)
                ->field("answers.$answerIndex.isAnonymously")->set($isAnonymously)
                ->field("answers.$answerIndex.updateDate")->set(time())
                ->upsert(false)
                ->getQuery()
                ->execute();
        $dm->flush();
        $dm->clear();

        $question = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->hydrate(false)
                ->field('_id')->equals($question->getId())
                ->getQuery()
                ->getSingleResult();

        $this->getSortedQuestion($question, $user);

        return new JsonResponse(array('question' => $question));
    }

    public function removeAnswerAction(Request $request) {
        $user = $this->getCurrentUser(); //Get current loggedin user
        if (!$user instanceof User) {
            throw new NotFoundHttpException();     // 404
        }

        $data = (object) $request->request->all();

        $questionId = $data->questionId;
        if (!isset($data->questionId)) {
            throw new NotFoundHttpException();     // 404
        }

        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $question = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->field('_id')->equals($questionId)
                ->field("answers.answeredBy.id")->equals($user->getId())
                ->getQuery()
                ->getSingleResult();

        if (!$question) {
            throw new NotFoundHttpException();
        }

        if (isset($data->answer['remove'])) {
            unset($data->answer['remove']);
        }

        //Refectored script
        /* if (isset($data->answer['ansIndex'])) {
          unset($data->answer['ansIndex']);
          } */

        if (isset($data->answer['connectionStatus'])) {
            unset($data->answer['connectionStatus']);
        }

        $answers = $question->getAnswers();
        if (isset($data->answer)) {
            $answerIndex = $data->answer['ansIndex']; //array_search($data->answer, $answers);//Refectored script
        } else if (isset($data->answerIndex)) {
            $answerIndex = $data->answerIndex;
        } else {
            throw new NotFoundHttpException();
        }
        $answer = $answers[$answerIndex];
        $upVote = - $answer['upVote'];
        $downVote = - $answer['downVote'];
        unset($answers[$answerIndex]);
        $answers = array_values($answers);

        $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->update()
                ->multiple(false)
                ->field('_id')->equals($questionId)
                ->field("answers.answeredBy.id")->equals($user->getId())
                ->field("answers")->set($answers)
                ->field("upVotes")->inc($upVote)
                ->field("downVotes")->inc($downVote)
                ->upsert(false)
                ->getQuery()
                ->execute();
        $dm->flush();
        $dm->clear();

        $question = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->hydrate(false)
                ->field('_id')->equals($question->getId())
                ->getQuery()
                ->getSingleResult();

        $this->getSortedQuestion($question, $user);

        $this->getMyAns($question, $user);

        return new JsonResponse(array('question' => $question));
    }

    public function voteForAnswerAction(Request $request) {
        $user = $this->getCurrentUser(); //Get current loggedin user
        if (!$user instanceof User) {
            throw new NotFoundHttpException();     // 404
        }

        $data = (object) $request->request->all();
        if (!isset($data->questionId)) {
            throw new NotFoundHttpException();     // 404
        }

        $questionId = $data->questionId;
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $question = $dm->getRepository('DataBaseBundle:Qa')->findOneBy(array('id' => $questionId));
        if (!$question) {
            throw new NotFoundHttpException();
        }

        $answers = $question->getAnswers() ? $question->getAnswers() : array();
        if (isset($data->answer)) {
            if (array_key_exists('ansIndex', $data->answer)) {
                unset($data->answer['ansIndex']);
            }

            if (isset($data->answer['connectionStatus'])) {
                unset($data->answer['connectionStatus']);
            }

            $answer = $data->answer;
            $index = array_search($answer, $answers);
        } else if (isset($data->answerIndex)) {
            $index = $data->answerIndex;
        } else {
            throw new NotFoundHttpException();     // 404
        }
        $type = isset($data->type) ? $data->type : 'upvote';
        if ($type === 'upvote' && $index !== false) {

            $dm->createQueryBuilder('DataBaseBundle:Qa')
                    ->update()
                    ->multiple(false)
                    ->field('_id')->equals($question->getId())
                    ->field("answers.$index.upVotedBy.id")->notEqual($user->getId())
                    ->field("answers.$index.upVotedBy")->addToSet(array(
                        'id' => $user->getId(),
                        'username' => $user->getUsername(),
                        'name' => $user->getName(),
                        'lastname' => $user->getLastname(),
                        'jobTitle' => $user->getJobTitle(),
                        'company' => $user->getCompany(),
                        'avatar' => $user->getAvatar(),
                        'cover' => $user->getCover(),
                    ))
                    ->field("answers.$index.upVote")->inc(1)
                    ->field("upVotes")->inc(1)
                    ->upsert(false)
                    ->getQuery()
                    ->execute();

            $dm->createQueryBuilder('DataBaseBundle:Qa')
                    ->update()
                    ->multiple(false)
                    ->field('_id')->equals($question->getId())
                    ->field("answers.$index.downVotedBy.id")->equals($user->getId())
                    ->field("answers.$index.downVotedBy")->pull(array('id' => $user->getId()))
                    ->field("answers.$index.downVote")->inc(-1)
                    ->field("downVotes")->inc(-1)
                    ->upsert(false)
                    ->getQuery()
                    ->execute();
        } else if ($type === 'downvote' && $index !== false) {

            $dm->createQueryBuilder('DataBaseBundle:Qa')
                    ->update()
                    ->multiple(false)
                    ->field('_id')->equals($question->getId())
                    ->field("answers.$index.downVotedBy.id")->notEqual($user->getId())
                    ->field("answers.$index.downVotedBy")->addToSet(array(
                        'id' => $user->getId(),
                        'username' => $user->getUsername(),
                        'name' => $user->getName(),
                        'lastname' => $user->getLastname(),
                        'jobTitle' => $user->getJobTitle(),
                        'company' => $user->getCompany(),
                        'avatar' => $user->getAvatar(),
                        'cover' => $user->getCover(),
                    ))
                    ->field("answers.$index.downVote")->inc(1)
                    ->field("downVotes")->inc(1)
                    ->upsert(false)
                    ->getQuery()
                    ->execute();

            $dm->createQueryBuilder('DataBaseBundle:Qa')
                    ->update()
                    ->multiple(false)
                    ->field('_id')->equals($question->getId())
                    ->field("answers.$index.upVotedBy.id")->equals($user->getId())
                    ->field("answers.$index.upVotedBy")->pull(array('id' => $user->getId()))
                    ->field("answers.$index.upVote")->inc(-1)
                    ->field("upVotes")->inc(-1)
                    ->upsert(false)
                    ->getQuery()
                    ->execute();
        }

        $dm->flush();
        $dm->clear();

        $question = $dm->createQueryBuilder('DataBaseBundle:Qa')
                ->hydrate(false)
                ->select('id', 'title', 'slug', 'details', 'askedBy', 'tags', 'isAnonymously', 'answers', 'pageViews', 'upVotes', 'downVotes', 'createDate', 'updateDate')
                ->field('id')->equals($questionId)
                ->getQuery()
                ->getSingleResult();

        //$question = $dm->getRepository('DataBaseBundle:Qa')->findOneBy(array('id' => $questionId));
        $this->getSortedQuestion($question, $user);
        //$this->getMostVotedAns($question);

        return new JsonResponse(array('question' => $question, 'answer' => $question['answers'][$index], 'mostVotedAns' => $question['answers'][0], 'mostVotedAnsIndex' => $question['answers'][0]['ansIndex']));
    }

    //Private Methods Blocks
    private function getCurrentUser() {
        if (null === $token = $this->get('security.context')->getToken()) {
            return null;
        }
        if (!is_object($user = $token->getUser())) {
            return null;
        }
        return $user;
    }

    private function questionsCounter($dm, $user) {
        $totalMyQuestions = 0;
        $totalMyAnswers = 0;
        $totalSaved = 0;
        if ($user) {
            $totalMyQuestions = $dm->createQueryBuilder('DataBaseBundle:Qa')
                    ->hydrate(false)
                    ->field('askedBy.id')->equals($user->getId())
                    ->getQuery()
                    ->execute()
                    ->count();

            $totalMyAnswers = $dm->createQueryBuilder('DataBaseBundle:Qa')
                    ->hydrate(false)
                    ->field('answers.answeredBy.id')->equals($user->getId())
                    ->getQuery()
                    ->execute()
                    ->count();

            $totalSaved = $dm->createQueryBuilder('DataBaseBundle:Qa')
                    ->hydrate(false)
                    ->field('savedBy.id')->equals($user->getId())
                    ->getQuery()
                    ->execute()
                    ->count();
        }
        return array('myQuestions' => $totalMyQuestions, 'myAnswers' => $totalMyAnswers, 'saved' => $totalSaved);
    }

    private function tagCounter($dm, $tag) {
        $arrTags = array(
            new \MongoRegex("/^{$tag}$/i")
        );

        $map = array('questions' => 0, 'pageViews' => 0);
        $reduce = 'function (obj, prev) { '
                . 'prev.questions++; '
                . 'prev.pageViews += obj.pageViews'
                . '}';

        $tagStats = $dm->createQueryBuilder('DataBaseBundle:Qa')
                        ->field('tags')->all($arrTags)
                        ->group(array(), $map)
                        ->reduce($reduce)
                        ->getQuery()
                        ->execute()
                        ->toArray()[0];
        return $tagStats;
    }

    private function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if (!array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }

    private function send_push_notification($heading, $content, $players, $url) {
        $heading = array("en" => $heading != null ? $heading : 'Mobintouch');

        $content = array("en" => $content);

        $fields = json_encode(array(
            'app_id' => $this->container->getParameter('onesignal_app_id'),
            'headings' => $heading,
            'contents' => $content,
            'include_player_ids' => $players,
            'url' => $url
        ));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->container->getParameter('onesignal_notification_url'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function imitateMerge(&$array1, &$array2) {
        foreach ($array2 as $i) {
            $array1[] = $i;
        }
    }

    private function removeAccents($str) {

        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');

        return str_replace($a, $b, $str);
    }

    private function cleanSlug($string, $separator = '-') {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array('&' => 'and', "'" => '', "?" => '');
        $string = mb_strtolower(trim($string), 'UTF-8');
        $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
        $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
    }

    private function getNearByJobs($dm, $user) {
        $arrJobs = array();
        if (isset($user) && $user && $user->getLat() && $user->getLng()) {
            $lat = $user->getLat();
            $lng = $user->getLng();
            $jobs = $dm->createQueryBuilder('DataBaseBundle:Jobs')
                    ->hydrate(false)
                    ->select('id', 'slug', 'company', 'jobTitle', 'jobType', 'location', 'currencySymbol', 'minSalary', 'maxSalary', 'equityMin', 'equityMax')
                    ->field('location.basedCountry')->equals($user->getBasedCountry())
                    ->field('location.city')->equals($user->getCity())
                    ->field('publishStatus')->equals('published')
                    ->field('appliedBy.id')->notEqual($user->getId())
                    ->getQuery()
                    ->execute();
        } else {
            $PublicIP = $this->get_client_ip();
            $json = file_get_contents("http://freegeoip.net/json/$PublicIP");
            $json = json_decode($json, true);
            $basedCountry = $json['country_code'];
            $lat = $json['latitude'];
            $lng = $json['longitude'];
            $jobs = $dm->createQueryBuilder('DataBaseBundle:Jobs')
                    ->hydrate(false)
                    ->select('id', 'slug', 'company', 'jobTitle', 'jobType', 'location', 'currencySymbol', 'minSalary', 'maxSalary', 'equityMin', 'equityMax')
                    ->field('location.basedCountry')->equals($basedCountry)
                    ->field('publishStatus')->equals('published')
                    ->getQuery()
                    ->execute();
        }

        if ($jobs && $lat && $lng) {
            foreach ($jobs as $job) {
                $job['id'] = $job['_id']->{'$id'};
                unset($job['_id']);
                $job['distance'] = $this->getDistance($lat, $lng, $job['location']['geomatryLocation']['lat'], $job['location']['geomatryLocation']['lng']);
                $arrJobs[] = $job;
            }
            usort($arrJobs, function($a, $b) {
                return $a['distance'] < $b['distance'];
            });
        }

        return $arrJobs;
    }

    private function getMostVotedAns(&$question) {
        $question['mostVotedAns'] = null;
        $ansCount = count($question['answers']);
        if ($ansCount > 0) {
            $answers = $question['answers'];
            usort($question['answers'], function($a, $b) {
                $q = $a['upVote'] < $b['upVote'];
//                $q .= $a['createDate'] < $b['createDate'];
                return $q;
            });
            $question['mostVotedAns'] = $question['answers'][0];
            $question['mostVotedAnsIndex'] = array_search($question['mostVotedAns'], $answers);
        }
    }

    private function getMyAns(&$question, &$user) {
        $question['myAnsCount'] = 0;
        $question['myAns'] = array();
        $ansCount = count($question['answers']);
        if ($ansCount > 0) {
            $question['myAns'] = array_map(function($k, $ans) use ($user) {
                if ($ans['answeredBy']['username'] === $user->getUsername()) {
                    $ans['myAnsIndex'] = $k;
                    return $ans;
                }
            }, array_keys($question['answers']), $question['answers']);

            usort($question['myAns'], function($a, $b) {
                $q = $a['upVote'] < $b['upVote'];
                //$q .= $a['createDate'] < $b['createDate'];
                return $q;
            });
            $question['myAnsCount'] = count($question['myAns']);
        }
    }

    private function getSortedQuestion(&$question, $user = null) {
        $question['id'] = $question['_id']->{'$id'};
        unset($question['_id']);

        $userConnectedIds = array();
        $userRequestIds = array();
        $userRequestedIds = array();

        if ($user) {
            $userConnections = $user->getInTouch() ? $user->getInTouch() : array();
            foreach ($userConnections as $uConnection) {
                if ($uConnection['status'] == 1) {
                    $userRequestedIds[] = $uConnection['id'];
                } else if ($uConnection['status'] == 2) {
                    $userRequestIds[] = $uConnection['id'];
                } else if ($uConnection['status'] == 3) {
                    $userConnectedIds[] = $uConnection['id'];
                }
            }
        }

        $question['answers'] = array_map(function($k, $ans) use($userConnectedIds, $userRequestIds, $userRequestedIds, $user) {
            $ans['ansIndex'] = $k;
            if (in_array($ans['answeredBy']['id'], $userConnectedIds)) {
                $ans['connectionStatus'] = 3;
            } else if (in_array($ans['answeredBy']['id'], $userRequestIds)) {
                $ans['connectionStatus'] = 2;
            } else if (in_array($ans['answeredBy']['id'], $userRequestedIds)) {
                $ans['connectionStatus'] = 1;
            } else {
                $ans['connectionStatus'] = 0;
            }
            //This lines are for making votes button enabled and disabled!
            $ans['upVotedBy'] = $this->array_column($ans['upVotedBy'], 'id');
            $ans['isUpVoted'] = $user && in_array($user->getId(), $ans['upVotedBy']) ? true : false;
            $ans['downVotedBy'] = $this->array_column($ans['downVotedBy'], 'id');
            $ans['isDownVoted'] = $user && in_array($user->getId(), $ans['downVotedBy']) ? true : false;

            return $ans;
        }, array_keys($question['answers']), $question['answers']);

        usort($question['answers'], function($a, $b) {
            $q = $a['upVote'] < $b['upVote'];
            //$q .= $a['createDate'] > $b['createDate'];
            return $q;
        });
    }

    private function getDistance($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
        // Calculate the distance in degrees
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat)) * sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat)) * cos(deg2rad($point2_lat)) * cos(deg2rad($point1_long - $point2_long)))));

        // Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
        switch ($unit) {
            case 'km':
                $distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
                break;
            case 'mi':
                $distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
                break;
            case 'nmi':
                $distance = $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
        }
        return round($distance, $decimals);
    }

    private function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}
