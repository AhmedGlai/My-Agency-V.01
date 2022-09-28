<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PropertyRepository $repository,EntityManagerInterface $em)
    {
        $this->repository=$repository;   
        $this->em=$em;
    }

    /**
     * @Route("/store",name="product.index")
     */
    public function index(PaginatorInterface $paginator, Request $request):Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$search);
        $form->handleRequest($request);

        $property =$paginator->paginate($this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('property/index.html.twig',[
            'current_menu'=>'property',
            'property'=>$property,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/store/{slug}-{id}", name="property.show", requirements={"slug":"[a-z0-9\-]*"})
     */
    public function show(Property $property,string $slug):Response
    {
        if($property->getSlug()!==$slug)
        {
            return $this->redirectToRoute('property.show',[
                'id'=>$property->getId(),
                'slug'=>$property->getSlug()
            ],301);
        }

        return $this->render('property/show.html.twig',[
            'property'=>$property,
            'current_menu'=>'property'
        ]);

    }

}