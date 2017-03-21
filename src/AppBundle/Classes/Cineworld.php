<?php

namespace AppBundle\Classes;

use AppBundle\Entity\Cinema;
use AppBundle\Entity\Date;
use AppBundle\Entity\Film;
use AppBundle\Entity\Performance;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Class Cineworld.
 *
 */
class Cineworld
{
    /**
     * Cineworld constructor.
     *
     * @param CineworldClient $client
     * @param Registry        $doctrine
     */
    public function __construct(
        CineworldClient $client,
        Registry $doctrine
    ) {
        $this->clinet = $client;
        $this->doctrine = $doctrine;
    }

    /**
     * @return Cinema[]
     */
    public function getCinemas()
    {
        $route = '/api/quickbook/cinemas';

        $response = $this->clinet->get($route);
        $content = $response->getBody()->getContents();

        $data = [];

        foreach (json_decode($content, true)['cinemas'] as $cinemaData) {
            $cinema = new Cinema($cinemaData);

            $existing = $this->findExistingCinema($cinema);

            if ($existing) {
                $cinema = $existing;
            }

            $data[$cinema->getId()] = $cinema;
        }

        return $data;
    }

    /**
     * @return Film[]
     */
    public function getFilms()
    {
        $route = '/api/quickbook/films';

        $response = $this->clinet->get($route);
        $content = $response->getBody()->getContents();
        $data = [];

        foreach (json_decode($content, true)['films'] as $cinemaData) {
            $film = new Film($cinemaData);

            $existing = $this->findExistingFilm($film);

            if ($existing) {
                $film = $existing;
            }

            $data[$film->getEdi()] = $film;
        }

        return $data;
    }

    /**
     * @return Date[]
     */
    public function getDates()
    {
        $route = '/api/quickbook/dates';

        $response = $this->clinet->get($route);
        $content = $response->getBody()->getContents();
        $data = [];

        foreach (json_decode($content, true)['dates'] as $cinemaData) {
            $date = new Date($cinemaData);

            $existing = $this->findExistingDate($date);

            if ($existing) {
                $date = $existing;
            }

            $data[$date->getDateFormatted()] = $date;
        }

        return $data;
    }

    /**
     * @param Date   $date
     * @param Cinema $cinema
     * @param Film   $film
     *
     * @return array
     */
    public function getPerformances(Date $date, Cinema $cinema, Film $film)
    {
        $manager = $this->doctrine->getManager();

        $route = 'pgm-site';

        $response = $this->clinet->get(
            $route,
            [
                'si' => $cinema->getSiteId(),
                'attr' => '2D,3D,IMAX,ViP,VIP,DBOX,4DX,M4J,SS',
                'code' => $film->getCode(),
                'vt' => 1,
                'near' => 1,
                'db' => $date->getApiDate()
            ]
        );

        $content = json_decode($response->getBody()->getContents(), true);
        $data = [];

        if (count($content) === 0) {
            return [];
        }

        foreach ($content[0]['P'] as $cinemaData) {
            $film->updateAdditionalData($cinemaData);

            foreach ($cinemaData['BD'][0]['P'] as $performance) {
                $performance = new Performance(
                    $date,
                    $cinema,
                    $film,
                    $performance
                );

                $manager->persist($performance);

                $data[] = $performance;
            }

            //$existing = $this->findExistingPerformance($film);
            //
            //if ($existing) {
            //    $film = $existing;
            //}

            $data[$film->getEdi()] = $film;
        }

        $manager->flush();

        return $data;
    }

    /**
     * @param Cinema $cinema
     *
     * @return Cinema
     */
    private function findExistingCinema(Cinema $cinema)
    {
        return $this->doctrine->getManager()->getRepository(Cinema::class)->find($cinema->getId());
    }

    /**
     * @param string $name
     *
     * @return Cinema
     */
    private function findCinemaByName($name)
    {
        return $this->doctrine->getManager()->getRepository(Cinema::class)->findOneBy(
            [
                'name' => $name,
            ]
        );
    }

    /**
     * @param string $name
     *
     * @return Film
     */
    private function findExistingPerformance(array $data)
    {
        return $this->doctrine->getManager()->getRepository(Performance::class)->findOneBy(
            [
            ]
        );
    }

    /**
     * @param Film $film
     *
     * @return Film
     */
    private function findExistingFilm(Film $film)
    {
        return $this->doctrine->getManager()->getRepository(Film::class)->findOneBy(
            [
                'edi' => $film->getEdi(),
            ]
        );
    }

    /**
     * @param Date $date
     *
     * @return Date
     */
    private function findExistingDate(Date $date)
    {
        return $this->doctrine->getManager()->getRepository(Date::class)->findOneBy(
            [
                'date' => $date->getDate()
            ]
        );
    }

    /**
     *
     */
    public function populateAdditionalCinemaData()
    {
        $route = 'getSites';

        $response = $this->clinet->get(
            $route,
            [
                'json' => 1,
                'max' => 200,
            ]
        );
        $content = $response->getBody()->getContents();

        foreach (json_decode($content, true) as $cinemaData) {
            $cinema = $this->findCinemaByName($cinemaData['n']);

            if ($cinema) {
                $cinema->updateAdditionalData($cinemaData);
            }
        }

        $route = 'api-backend/get-sites';

        $response = $this->clinet->get($route);
        $content = $response->getBody()->getContents();

        foreach (json_decode($content, true)['data'] as $cinemaData) {
            $cinema = $this->findCinemaByName($cinemaData['name']);

            if ($cinema) {
                $cinema->updateAdditionalData2($cinemaData);
            }
        }
    }

    public function populateAllCinemas()
    {
        $manager = $this->doctrine->getManager();

        foreach ($this->getCinemas() as $cinema) {
            $manager->persist($cinema);
        }

        $manager->flush();

        $this->populateAdditionalCinemaData();

        $manager->flush();
    }

    public function populateAllFilms()
    {
        $manager = $this->doctrine->getManager();

        foreach ($this->getFilms() as $cinema) {
            $manager->persist($cinema);
        }

        $manager->flush();
    }

    public function populateAllDates()
    {
        $manager = $this->doctrine->getManager();

        foreach ($this->getDates() as $cinema) {
            $manager->persist($cinema);
        }

        $manager->flush();
    }

    /**
     *
     */
    public function populateAllPerformances()
    {
        $em = $this->doctrine->getManager();

        foreach ($em->getRepository(Date::class)->findAll() as $date) {
            if ($date->getDateFormatted() !== '20170325') {
                continue;
            }

            foreach ($em->getRepository(Cinema::class)->findAll() as $cinema) {
                if ($cinema->getId() !== Cinema::HULL) {
                    continue;
                }

                foreach ($em->getRepository(Film::class)->findAll() as $film) {
                    $performances = $this->getPerformances(
                        $date,
                        $cinema,
                        $film
                    );

                    foreach ($performances as $permormance) {
                        $em->persist($permormance);
                    }

                    $em->flush();
                }
            }
        }
    }

    public function bookEvents(\DateTime $dateTime)
    {
        $em = $this->doctrine->getManager();

        $performances = $em->getRepository(Performance::class)->
    }
}
