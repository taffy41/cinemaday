<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="Cineworld.tblFilm", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UK_intEdi", columns={"intEdi"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FilmRepository")
 *
 * @UniqueEntity(fields={"edi"})
 */
class Film
{
    /**
     * @var int
     *
     * @ORM\Column(name="intFilmId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="strTitle", type="string")
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="intEdi", type="integer")
     */
    private $edi;

    /**
     * @var string
     *
     * @ORM\Column(name="strFilmUrl", type="string")
     */
    private $filmUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="strClassification", type="string")
     */
    private $classification;

    /**
     * @var string
     *
     * @ORM\Column(name="strAdvisory", type="string", nullable=true)
     */
    private $advisory;

    /**
     * @var string
     *
     * @ORM\Column(name="strPosterUrl", type="string")
     */
    private $posterUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="strStilUrl", type="string", nullable=true)
     */
    private $stillUrl;

    /**
     * @var bool
     *
     * @ORM\Column(name="bol3D", type="boolean")
     */
    private $is3D;

    /**
     * @var bool
     *
     * @ORM\Column(name="bolImax", type="boolean")
     */
    private $isImax;

    /**
     * @var string
     *
     * @ORM\Column(name="strCode", type="string", nullable=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="intDuration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="strRestriction", type="string", nullable=true)
     */
    private $restriction;
    /**
     * @var string
     *
     * @ORM\Column(name="strRestrictionLong", type="string", nullable=true)
     */
    private $restrictionLong;

    /**
     * @var string
     *
     * @ORM\Column(name="strRestrictionImageUrl", type="string", nullable=true)
     */
    private $restrictionImageUrl;

    /**
     * @var Performance[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Performance", mappedBy="film", cascade="all")
     */
    private $performances;

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

        $this->title = $data['title'];
        $this->edi = (int) $data['edi'];
        $this->filmUrl = $data['film_url'];
        $this->classification = $data['classification'];
        $this->advisory = $data['advisory'];
        $this->posterUrl = $data['poster_url'];
        $this->stillUrl = $data['still_url'];
        $this->is3D = (bool) $data['3D'];
        $this->isImax = (bool) $data['imax'];

        if ($this->posterUrl) {
            $this->code = str_replace(['https://www.cineworld.co.uk/xmedia-cw/repo/feats/posters/', '.jpg'], '', $this->posterUrl);
        }

        $this->performances = new ArrayCollection();
    }

    /**
     * @param array $data
     *
     * @throws \Exception
     */
    private function validate(array $data)
    {
        $fields = [
            'title',
            'edi',
            'film_url',
            'classification',
            'advisory',
            'poster_url',
            'still_url',
            '3D',
            'imax',
        ];

        foreach ($fields as $field) {
            if (!array_key_exists($field, $data)) {
                throw new \Exception(
                    sprintf(
                        'Missing %s Field: %s',
                        $field,
                        implode(', ', array_keys($data))
                    )
                );
            }
        }
    }

    /**
     * @param array $data
     */
    public function updateAdditionalData(array $data)
    {
        $this->duration = $data['dur'];
        $this->restriction = $data['rn'];
        $this->restrictionLong = $data['rdesc'];
        $this->restrictionImageUrl = 'https://www.cineworld.co.uk/xmedia-cw/' . $data['rfn'];
    }

    /**
     * @param Performance $performance
     */
    public function addPerformance(Performance $performance)
    {
        $this->performances->add($performance);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getEdi(): int
    {
        return $this->edi;
    }

    /**
     * @return string
     */
    public function getFilmUrl(): string
    {
        return $this->filmUrl;
    }

    /**
     * @return string
     */
    public function getClassification(): string
    {
        return $this->classification;
    }

    /**
     * @return string
     */
    public function getAdvisory(): string
    {
        return $this->advisory;
    }

    /**
     * @return string
     */
    public function getPosterUrl(): string
    {
        return $this->posterUrl;
    }

    /**
     * @return string
     */
    public function getStillUrl(): string
    {
        return $this->stillUrl;
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
    public function isIsImax(): bool
    {
        return $this->isImax;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getRestriction(): string
    {
        return $this->restriction;
    }

    /**
     * @return string
     */
    public function getRestrictionLong(): string
    {
        return $this->restrictionLong;
    }

    /**
     * @return string
     */
    public function getRestrictionImageUrl(): string
    {
        return $this->restrictionImageUrl;
    }

    /**
     * @return Performance[]
     */
    public function getPerformances(): array
    {
        return $this->performances;
    }
}
