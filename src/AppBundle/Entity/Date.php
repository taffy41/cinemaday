<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="Cineworld.tblDate", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UK_tblFilm_dtmDate", columns={"dtmDate"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DateRepository")
 *
 * @UniqueEntity(fields={"name"})
 */
class Date
{
    /**
     * @var int
     *
     * @ORM\Column(name="intDateId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmDate", type="datetime")
     */
    private $date;

    /**
     * @var Performance[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Performance", mappedBy="date", cascade="all")
     */
    private $performances;

    /**
     * Date constructor.
     *
     * @param $dateString
     */
    public function __construct($dateString)
    {
        $this->date = new \DateTime($dateString);
        $this->date->setTime(0, 0, 0);
        $this->performances = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getDateFormatted()
    {
        return $this->date->format('Ymd');
    }
    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->date->format('d.m.Y');
    }

    /**
     * @param Performance $performance
     */
    public function addPerformance(Performance $performance)
    {
        $this->performances->add($performance);
    }

    public function getApiDate()
    {
        return $this->getDate()->format('U') * 1000;
    }
}
