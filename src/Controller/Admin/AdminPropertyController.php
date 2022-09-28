<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(PropertyRepository $repository,EntityManagerInterface $manager)
    {
        $this->repository=$repository;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     *
     */
    public function new(Request $request)
    {

        $property = new Property();
        $form= $this->createForm(PropertyType::class,$property);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->manager->persist($property);
            $this->manager->flush();
            $this->addFlash('success','DATA CREATED SUCCESSFULLY');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('Admin/property/new.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/property/{id}",name="admin.property.delete",methods="DELETE")
     */
    public function delete(Property $property,Request $request): RedirectResponse
    {
        //if($this->isCsrfTokenValid('delete'.$property->getId(),$request->get('_token')))
        $this->manager->remove($property);
        $this->manager->flush();
        $this->addFlash('success','DATA DELETED SUCCESSFULLY');
        return $this->redirectToRoute('admin.property.index');
    }

    /**
     * @Route("/admin",name="admin.property.index")
     * @return Response
     */
    public function index(): Response
    {
        $property = $this->repository->findAll();
        return $this->render('Admin/property/index.html.twig',compact('property'));
    }

    /**
     * @Route("/admin/property/{id}",name="admin.property.edit",methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function edit(Property $property,Request $request): Response
    {
        $form=$this->createForm(PropertyType::class,$property);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid())
        {
            $this->manager->flush();
            $this->addFlash('success','DATA MODIFIED SUCCESSFULLY');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('Admin/property/edit.html.twig',[
            'property'=>$property,
            'form'=>$form->createView()
        ]);
    }





}