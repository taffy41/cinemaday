<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="Cineworld.tblPerformance", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UK_tblPerformance_intDateId_intCinemaId_intFilmId_dtmTime", columns={"intDateId", "intCinemaId", "intFilmId", "dtmTime"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PerformanceRepository")
 *
 * @UniqueEntity(fields={"date", "cinema", "film", "time"})
 */
class Performance
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Date
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Date")
     * @ORM\JoinColumn(name="intDateId", referencedColumnName="intDateId", nullable=false)
     */
    private $date;

    /**
     * @var Cinema
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cinema", inversedBy="performances")
     * @ORM\JoinColumn(name="intCinemaId", referencedColumnName="intCinemaId", nullable=false)
     */
    private $cinema;

    /**
     * @var Film
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Film", inversedBy="performances")
     * @ORM\JoinColumn(name="intFilmId", referencedColumnName="intFilmId", nullable=false)
     */
    private $film;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmTime", type="datetime")
     */
    private $time;

    /**
     * @var bool
     *
     * @ORM\Column(name="bolSubtitled", type="boolean")
     */
    private $isSubtitled;

    /**
     * @var bool
     *
     * @ORM\Column(name="bol3D", type="boolean")
     */
    private $is3D;

    /**
     * @var bool
     *
     * @ORM\Column(name="bolSold", type="boolean")
     */
    private $isSold;

    /**
     * @var string
     *
     * @ORM\Column(name="strCode", type="string")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="strString", type="string")
     */
    private $screen;

    /**
     * Performance constructor.
     *
     * @param Date   $date
     * @param Cinema $cinema
     * @param Film   $film
     * @param array  $data
     */
    public function __construct(
        Date $date,
        Cinema $cinema,
        Film $film,
        array $data
    ) {
        $this->date = $date;
        $this->cinema = $cinema;
        $this->film = $film;
dump($data);
        $this->time = clone $this->date->getDate();
        $this->time->modify($data['time']);
        $this->is3D = (bool) isset($data['is3d']) ? $data['is3d'] : false;
        $this->isSubtitled = (bool) isset($data['sub']) ? $data['sub'] : false;
        $this->isSold = (bool) isset($data['sold']) ? $data['sold'] : false;
        $this->screen = isset($data['vn']) ? $data['vn'] : '';
        $this->code = isset($data['code']) ? $data['code'] : '';

        $this->film->addPerformance($this);
        $this->cinema->addPerformance($this);
        $this->date->addPerformance($this);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Date
     */
    public function getDate(): Date
    {
        return $this->date;
    }

    /**
     * @return Cinema
     */
    public function getCinema(): Cinema
    {
        return $this->cinema;
    }

    /**
     * @return Film
     */
    public function getFilm(): Film
    {
        return $this->film;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @return bool
     */
    public function isIsSubtitled(): bool
    {
        return $this->isSubtitled;
    }

    /**
     * @return bool
     */
    public function isIs3D(): bool
    {
        return $this->is3D;
    }

    /**
     * @return bool
     */
    public function isIsSold(): bool
    {
        return $this->isSold;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getScreen(): string
    {
        return $this->screen;
    }
}
