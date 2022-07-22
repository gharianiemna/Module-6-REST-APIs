<?php

namespace App\DataFixtures;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{

    private $userPasswordEncoder;

    public function __construct( UserPasswordEncoderInterface $userPasswordEncoder){
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    public function load(ObjectManager $manager): void
    {
    $userList=[
        [   
            "email"=>"admin@talan.com",
            "Password"=> "admin123",
            "roles"=> ["ROLE_ADMIN"],
        ],
        [   
            "email"=>"emna@talan.com",
            "Password"=> "emna123",
            "roles"=>  ["ROLE_USER"],
        ],
        [   
            "email"=>"habib@talan.com",
            "Password"=> "habib123",
            "roles"=>   ["ROLE_USER"],
        ],
        [  
            "email"=>"samar@talan.com",
            "Password"=> "samar123",
            "roles"=>  ["ROLE_USER"],
        ],
        [   
            "email"=>"kenza@talan.com",
            "Password"=> "kenza123",
            "roles"=>  ["ROLE_USER"],
        ],
        [  
            "email"=>'rania@talan.com',
            "Password"=> "rania123",
            "roles"=>  ["ROLE_USER"],
        ],
    ];
        foreach ($userList as $users){
            $user =new User();
            $user->setEmail( $users["email"])
                ->setPassword($this->userPasswordEncoder->encodePassword($user,$users["Password"]))
                ->setRoles( $users["roles"]);    
            $manager->persist($user); 
        }
        $manager->flush();
        }
    public function getDependencies()
    {
            return array(
              UserFixture::class,
            );
    }
}
