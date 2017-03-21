<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Cinema
 *
 * @ORM\Table(name="Cineworld.tblCinema", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UK_tblCinema_strName", columns={"strName"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CinemaRepository")
 *
 * @UniqueEntity(fields={"name"})
 */
class Cinema
{
    const HULL = 35;

    /**
     * @var int
     *
     * @ORM\Column(name="intCinemaId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="strName", type="string")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="intSiteId", type="integer")
     */
    private $siteId;

    /**
     * @var string
     *
     * @ORM\Column(name="strUrl", type="string")
     */
    private $url;

    /**
     * @var Performance[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Performance", mappedBy="cinema", cascade="all")
     */
    private $performances;

    /**
     * @var int
     *
     * @ORM\Column(name="intExcode", type="integer")
     */
    private $externalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="strAddress", type="string")
     */
    private $address;

    /**
     * @var int
     *
     * @ORM\Column(name="intIndex", type="integer")
     */
    private $index;

    /**
     * @var string
     *
     * @ORM\Column(name="strPhone", type="string")
     */
    private $phoneNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="decLongitude", type="float", scale=5, precision=6)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="decLatitude", type="float", scale=5, precision=6)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="strImageUrl", type="string")
     */
    private $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="strAddress1", type="string")
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="strAddress2", type="string")
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="strCity", type="string")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="strPostCode", type="string")
     */
    private $postCode;

    /**
     * Cinema constructor.
     *
     * @param array $data
     *
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->validate($data);

        $this->id = (int) $data['id'];
        $this->name = $data['name'];
        $this->url = $data['cinema_url'];

        $this->performances = new ArrayCollection();
    }

    /**
     * @param array $data
     */
    public function updateAdditionalData(array $data)
    {
        $this->siteId = (int) $data['id'];
        $this->address = $data['addr'];
        $this->index = (int) $data['idx'];
        $this->phoneNumber = str_replace(' ', '', $data['pn']);}

    /**
     * @param array $data
     */
    public function updateAdditionalData2(array $data)
    {
        $this->imageUrl = 'https://www.cineworld.co.uk/xmedia-cw/repo/sites/' . $data['filename'];
        $this->address1 = $data['address']['address1'];
        $this->address2 = $data['address']['address2'];
        $this->city = $data['address']['city'];
        $this->postCode = $data['address']['postalCode'];
        $this->longitude =  (float)  $data['longitude'];
        $this->latitude = (float) $data['latitude'];
    }

    /**
     * @param array $data
     *
     * @throws \Exception
     */
    private function validate(array $data)
    {
        if (!array_key_exists('id', $data)) {
            throw new \Exception('Missing Id Field: ' . implode(', ', array_keys($data)));
        }

        if (!array_key_exists('name', $data)) {
            throw new \Exception('Missing Name Field: ' . implode(', ', array_keys($data)));
        }

        if (!array_key_exists('cinema_url', $data)) {
            throw new \Exception('Missing Cinema Url Field: ' . implode(', ', array_keys($data)));
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @param Performance $performance
     */
    public function addPerformance(Performance $performance)
    {
        $this->performances->add($performance);
    }
}

