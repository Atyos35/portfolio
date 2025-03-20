<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\Training;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('Valérian');
        $user->setLastname('Guémené');
        $user->setJob('Développeur Web Full-Stack');
        $user->setLinkedin('https://www.linkedin.com/in/valerian-guemene/');
        $user->setEmail('valerian.guemene@gmail.com');
        $user->setAge('34');
        $user->setCity('Rennes');
        $user->setPhone('0684445191');

        $password = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($password);

        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $experiences = [
            ["Développeur WEB", "Groupe Pandora", "2022-09-01", null, "Évolution et maintenance de l'application de gestion de matériel ainsi que sur le CRM interne."],
            ["Concepteur Développeur Web", "Samsic Groupe", "2021-10-01", "2022-04-01", "Conception et développement d'une application Web d'export et d'import de données."],
            ["Commercial", "Commercial Academy", "2020-10-01", "2021-03-01", "Prospection, Phoning, Gestion de la clientèle, Plan de tournée, Technique de négociation."],
            ["Concepteur Développeur Web", "Néo-Soft Groupe", "2019-07-01", "2019-08-01", "Développement d'une application Web sur l'intranet de l'entreprise."],
            ["Développeur Web", "NEO-SOFT, consultant pour OUEST-FRANCE", "2018-09-01", "2019-06-01", "Maintenance applicative et évolutive d'une application Web de gestion d'abonnements."],
            ["Concepteur Développeur Web en alternance", "CER France", "2016-11-01", "2018-06-01", "Refonte d'application WEB de création de lettre de mission."],
            ["Développeur Web", "L’univers NOZ", "2015-09-01", "2016-09-01", "Gestion des droits utilisateurs, création de formulaires, développement de l'administration."],
        ];

        foreach ($experiences as [$name, $company, $startDate, $endDate, $description]) {
            $experience = new Experience();
            $experience->setName($name);
            $experience->setCompany($company);
            $experience->setStartDate(new \DateTime($startDate));
            $experience->setEndDate($endDate ? new \DateTime($endDate) : null);
            $experience->setDescription($description);
            $experience->setUser($user);
            $manager->persist($experience);
        }

        $trainings = [
            ['Concepteur Développeur en Projets Numériques', 'Titre de niveau II (BAC+4)', 'IMIE Bruz', '2016-09-01', '2018-07-01'],
            ['Développeur Logiciel', 'Titre de niveau III (BAC+2)', 'IMIE Bruz', '2014-09-01', '2016-07-01'],
            ['BAC PRO Logistique', '', 'Lycée Bel Air Tinténiac', '2009-09-01', '2011-06-30'],
            ['BEP CSTR', '', 'Lycée Bel Air Tinténiac', '2006-09-01', '2009-07-01']
        ];

        foreach ($trainings as [$name, $description, $school, $startDate, $endDate]) {
            $training = new Training();
            $training->setName($name);
            $training->setDescription($description);
            $training->setSchool($school);
            $training->setStartDate(new \DateTime($startDate));
            $training->setEndDate(new \DateTime($endDate));
            $training->setUser($user);

            $manager->persist($training);
        }

        $manager->flush();
    }
}
