<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="Cineworld.tblBooking", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UK_tblBooking_intPerformanceId_dtmStartTime"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 *
 * @UniqueEntity(fields={"date", "cinema", "film", "startTime"})
 */
class Booking
{
    /**
     * @var int
     *
     * @ORM\Column(name="intBookingId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Performance
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cinema", inversedBy="performances")
     * @ORM\JoinColumn(name="intCinemaId", referencedColumnName="intCinemaId", nullable=false)
     */
    private $performance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmStartTime", type="datetime")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmEndTime", type="datetime")
     */
    private $endTime;

    /**
     * Performance constructor.
     *
     * @param Performance $performance
     */
    public function __construct(Performance $performance)
    {
        $this->performance = $performance;
        $this->startTime = $performance->getTime();
        $this->endTime = (clone ($performance->getTime()))->modify('+' . $performance->getFilm()->getDuration() . ' mins');
    }
}
