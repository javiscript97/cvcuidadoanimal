<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Cliente;
use App\Entity\Mascotas;
use App\Entity\Producto;
use App\Entity\Historial;
use App\Entity\Veterinario;
use Doctrine\ORM\Mapping\Id;
use App\Entity\Administrador;
use App\Repository\MascotasRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Tests\Models\Enums\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $cliente1 = new Cliente();
        $cliente1->setNombre('David');
        $cliente1->setEdad(28);
        $cliente1->setDireccion('Lorca');
        $cliente1->setMail('Davide@gmail.com');
        $cliente1->setPassword('1234');
        $cliente1->setTelefono('689262724');
        $cliente1->setRol('Cliente');
        $manager->persist($cliente1);

        $mascota1 = new Mascotas();
        $mascota1->setNombre('Coco');
        $mascota1->setEdad(11);
        $mascota1->setRaza('Snaugther');
        $mascota1->setAnimal('Perro');
        $mascota1->setGenero('Macho');
        $mascota1->setFicha('Alergia al polen');
        $mascota1->setClienteId($cliente1);
        $manager->persist($mascota1);

        
        $vet1 = new Veterinario();
        $vet1->setNombre('Carlos');
        $vet1->setEdad(56);
        $vet1->setMail('carlos@gmail.com');
        $vet1->setPassword('1234');
        $vet1->setRol('Vet');
        $vet1->setEspecialidad('Cirugia');
        $manager->persist($vet1);


    
        $historial1 = new Historial();
        $historial1->setDescripcion('Alergia aguda al polen, se le receta antihistaminicos');
        $historial1->setFecha(new DateTime());
        $historial1->setMascotaId($mascota1);
        $historial1->setVetId($vet1);
        $manager->persist($historial1);


        //Productos 
        $product1 = new Producto();
        $product1->setNombre('Milbermax'); 
        $product1->setDescripcion('Medicamento desparasitario para infecciones con lombrices');
        $product1->setStock(10);
        $product1->setPrecio(11.49);
        $product1->setCaducidad(new DateTime());
        $manager->persist($product1);

        $product2 = new Producto();
        $product2->setNombre('Hyaloral'); 
        $product2->setDescripcion('Prevención de displasia');
        $product2->setStock(10);
        $product2->setPrecio(30.88);
        $product2->setCaducidad(new DateTime());
        $manager->persist($product2);

        $product3 = new Producto();
        $product3->setNombre('Soludex'); 
        $product3->setDescripcion('Desinfectante');
        $product3->setStock(10);
        $product3->setPrecio(13.73);
        $product3->setCaducidad(new DateTime());
        $manager->persist($product3);

        $product4 = new Producto();
        $product4->setNombre('Otoclean'); 
        $product4->setDescripcion('Limpiador Ótico');
        $product4->setStock(10);
        $product4->setPrecio(17.67);
        $product4->setCaducidad(new DateTime());
        $manager->persist($product4);

        $product5 = new Producto();
        $product5->setNombre('Aceprovet'); 
        $product5->setDescripcion('Sedante');
        $product5->setStock(10);
        $product5->setPrecio(14.99);
        $product5->setCaducidad(new DateTime());
        $manager->persist($product5);

        $product6 = new Producto();
        $product6->setNombre('Lagrimet Pro'); 
        $product6->setDescripcion('Solución ocular que limpia y protege los ojos de los perros');
        $product6->setStock(10);
        $product6->setPrecio(10.35);
        $product6->setCaducidad(new DateTime());
        $manager->persist($product6);

        $manager->flush();
    }
}
