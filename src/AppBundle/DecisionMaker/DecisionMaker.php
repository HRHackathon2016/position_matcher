<?php

namespace AppBundle\DecisionMaker;

use AppBundle\Model\User;

class DecisionMaker
{
    public function decide(User $user, $jobProperties)
    {
        try {
            $this->isAccepted($user->getLanguages(), explode(',', $jobProperties['Language']));
            $this->isAccepted($user->getSkills(), explode(',', $jobProperties['Expertise']));

            return true;
        } catch (NotAcceptedException $e) {
            return false;
        }
    }

    private function isAccepted($have, $requirements)
    {
        $have = array_map('strtolower', $have);
        $requirements = array_map('strtolower', $requirements);
        $groups = [];
        foreach ($requirements as $key => $requirement) {
            $group = null;
            $optional = true;
            $lastChar = $this->getLastChar($requirement);
            if (is_numeric($lastChar)) {
                $group = $lastChar;
                $requirement = $this->removeLastChar($requirement);
                $lastChar = $this->getLastChar($requirement);
            }

            if (in_array($lastChar, ['!', '?']))
            {
                $requirement = $requirement = $this->removeLastChar($requirement);
                if ($lastChar == '!') {
                    $optional = false;
                }
            }

            if (!isset($groups[$group])) {
                $groups[$group] = [
                    'requirements' => [],
                    'optional' => $optional,
                ];
            }

            if (null === $group) {
                $group = $key;
            }

            $groups[$group]['requirements'] = $requirement;
        }

        foreach ($groups as $group) {
            if (!$group['optional'] && !count(array_intersect($group['requirements'], $have))) {
                throw new NotAcceptedException();
            }
        }

        return true;
    }

    private function getLastChar($string)
    {
        return $string[strlen($string)-1];
    }

    private function removeLastChar($string)
    {
        return substr($string, 0, strlen($string) - 1);
    }
}


/**
[
  {
    "Eperience Level": [
      "Entry-level?",
      "Junior?",
      "Senior?"
    ]
  },
  {
    "Expertise": [
      "Agile!",
      "TDD!",
      "native Android!1",
      "Android SDK!1",
      "Scrum?",
      "XML?",
      "MVC?",
      "MVP?",
      "MVM",
      "Design pattern",
      "TDD",
      "BDD",
      "Reactive Programming",
      "Continuous Integration",
      "Kanban"
    ]
  },
  {
    "Personality": "Analyzer,creative cookie,Team player,written comminicator,verbal communicator,Value creator,Curious Learner"
  },
  {
    "Job Title": "Entry Level Software engineer - Android"
  },
  {
    "Link": "http:\/\/company.trivago.de\/jobs\/1317\/"
  },
  {
    "Responsibilities": [
      "Develop our mobile apps in an agile environment",
      "Use your expert knowledge to bring fresh new ideas to the table",
      "Analyze feature requests and then plan & implement them in a test-driven way",
      "Create and review pull requests in order to achieve the best possible solution",
      "Contribute to the maintenance and development of new and existing open source components",
      "Exchange ideas and share your knowledge with the rest of the team and participate in pair programming"
    ]
  },
  {
    "The ideal candidate:": [
      "Is looking for a new challenge!",
      "Has earned a degree\/equivalent qualification in computer science or similar field",
      "Can prove their technical skills in native Android development and can share code references",
      "Has experience in dealing with Android SDK",
      "design patterns and software architectures such as MVC\/MVP\/MVVM",
      "Is genuinely interested in agile development processes and automated testing",
      "Experience in these areas would be a plus",
      "Is familiar with Scrum",
      "Kanban",
      "TDD",
      "BDD",
      "Reactive Programming and Continuous Integration",
      "Speaks English fluently",
      "German language skills would be a plus"
    ]
  }
]
 */
