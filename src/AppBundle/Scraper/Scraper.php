<?php

namespace AppBundle\Scraper;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;


/**
 * Created by PhpStorm.
 * User: mbrea
 * Date: 16/04/16
 * Time: 12:49
 */
class Scraper
{
    private $name;
    private $skills = [];
    /** @var Job[] */
    private $jobHistory = [];
    private $languages = [];
    private $education = [];

    private function getContent($url){
        $client = new Client();
        $o = $client->get($url,
            ['headers'=> ['accept-language'=> 'en-US',
             'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36']]);

        return $o->getBody()->getContents();
    }

    public function getData($url)
    {
        $html = $this->getContent($url);
        $crawler = new Crawler($html);
        $this->dataScraper($crawler);

        $data = array(
            'name' => $this->getName(),
            //'education' => $this->getEducation(),
            'jobs' => $this->getJobHistory(),
            'skills' => $this->getSkills(),
            'languages' => $this->getLanguages(),
        );

        return $data;
    }

    private function dataScraper (Crawler $crawler){
        //$this->setEducation($this->filterEducation($crawler));
        $this->setJobHistory($this->filterJobHistory($crawler));
        $this->setSkills($this->filterApplicantSkills($crawler));
        $this->setName($this->filterName($crawler));
        $this->setLanguages($this->filterLanguage($crawler));
    }

    public function filterEducation(Crawler $crawler)
    {
        $educationTitles = [];
        try{
            $educationDom = $crawler->filter('#education ul')->children();
            $educationDom->each(function(\Symfony\Component\DomCrawler\Crawler $education) use (&$educationTitles){
                array_push($educationTitles, $education->filter('.item-subtitle')->text());
            });
        }catch (\Exception $e){}

        return $educationTitles;
    }

    public function filterLanguage(Crawler $crawler)
    {
        $languages = [];
        try{
            $languagesDom = $crawler->filter('#languages ul')->children();
            $languagesDom->each(function(\Symfony\Component\DomCrawler\Crawler $language) use (&$languages){
                array_push($languages, $language->filter('.name')->text());
            });

        }catch (\Exception $e){}

        return $languages;
    }

    public function filterJobHistory(Crawler $crawler){
        $jobHistory = [];
        try{
            $jobs = $crawler->filter('.positions')->children();
            $jobs->each(function(\Symfony\Component\DomCrawler\Crawler $skill) use (&$jobHistory){
                $jobTitle = $skill->filter('.item-title')->text();
                $companyName = $skill->filter('.item-subtitle')->text();
                $duration = $this->convertToMonth($skill->filter('.date-range')->text());
                $job = new Job($jobTitle, $companyName, $duration);

                array_push($jobHistory, $job);
            });
        }catch (\Exception $e){}

        return $jobHistory;
    }

    public function convertToMonth($duration){
        preg_match('/.*(\(((\d+) (year|month)(s?)|(\d+) (year)(s?) (\d+) (month)(s?)))\)/', $duration, $matches);
        $monthDuration = 0;
        foreach ($matches as $i => $value ) {
            if (is_numeric($value) && $value != ''){
                $type = $matches[$i+1];
                if ($type === 'year'){
                    $monthDuration = $monthDuration + ((int) $value * 12);
                }
                if ($type === 'month'){
                    $monthDuration = $monthDuration + $value;
                }
            }
        }

        return $monthDuration;
    }

    public function filterApplicantSkills(Crawler $crawler){
        $aApplicantSkills = [];
        try{
            $aSkills = $crawler->filter('.pills')->children();
            $aSkills->each(function(\Symfony\Component\DomCrawler\Crawler $skill) use (&$aApplicantSkills){
                $skillText = $skill->text();
                if (is_string($skillText) && $skillText != '' && strtolower($skillText) != 'see less'){
                    array_push($aApplicantSkills,$skillText);
                }
            });
        }catch (\Exception $e){

        }

        return $aApplicantSkills;
    }

    public function filterName(Crawler $crawler){
        return $crawler->filter('#name')->text();
    }

    /**
     * @return Job[]
     */
    public function getJobHistory()
    {
        return $this->jobHistory;
    }

    /**
     * @param Job[] $jobHistory
     */
    public function setJobHistory($jobHistory)
    {
        $this->jobHistory = $jobHistory;
    }

    /**
     * @return array
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param array $skills
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    }

    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param mixed $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @return mixed
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @param mixed $education
     */
    public function setEducation($education)
    {
        $this->education = $education;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}