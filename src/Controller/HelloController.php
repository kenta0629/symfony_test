<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
// use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HelloController extends AbstractController
{

    /**
     * ネイティブSQLの使用方法
     *
     * @Route("/find", name="find")
     */
    public function find(Request $request): Response
    {
        $formobj = new FindForm();
        $form = $this->createFormBuilder($formobj)
            ->add('find', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Click'])
            ->getForm();

        $repository = $this->getDoctrine()
            ->getRepository(Person::class);

        $manager = $this->getDoctrine()->getManager();
        $mapping = new ResultSetMappingBuilder($manager);
        $mapping->addRootEntityFromClassMetadata('App\Entity\Person', 'p');

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $finder = $form->getData()->getFind();
            $arr = explode(',', $finder);

            // FIXME:BETWEENでSyntaxエラーになる
            $query = $manager->createNativeQuery(
                'SELECT * FROM person WHERE age BETWEEN ?1 AND ?2',
                $mapping
            )
            ->setParameters([1 => $arr[0], 2 => $arr[1]]);
            $result = $query->getResult();
        } else {
            $query = $manager->createNativeQuery('SELECT * FROM person', $mapping);
            $result = $query->getResult();
        }

        return $this->render('hello/find.html.twig', [
            'title' => 'Hello',
            'form' => $form->createView(),
            'data' => $result
        ]);
    }

    // /**
    //  * リポジトリのメソッド連携
    //  * DQLの使用方法
    //  *
    //  * @Route("/find", name="find")
    //  */
    // public function find(Request $request): Response
    // {
    //     $formobj = new FindForm();
    //     $form = $this->createFormBuilder($formobj)
    //         ->add('find', TextType::class)
    //         ->add('save', SubmitType::class, ['label' => 'Click'])
    //         ->getForm();

    //     $repository = $this->getDoctrine()
    //         ->getRepository(Person::class);

    //     $manager = $this->getDoctrine()->getManager();

    //     if ($request->getMethod() == 'POST') {
    //         $form->handleRequest($request);
    //         $finder = $form->getData()->getFind();

    //         $query = $manager->createQuery(
    //             "SELECT p FROM App\Entity\Person p
    //             WHERE p.name = '{$finder}'"
    //         );
    //         $result = $query->getResult();
    //     } else {
    //         $result = $repository->findAllwithSort();
    //     }

    //     return $this->render('hello/find.html.twig', [
    //         'title' => 'Hello',
    //         'form' => $form->createView(),
    //         'data' => $result
    //     ]);
    // }

    // /**
    //  * ORMの使用方法1
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request): Response
    // {
    //     $repository = $this->getDoctrine()
    //         ->getRepository(Person::class);

    //     $data = $repository->findall();

    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'data' => $data
    //     ]);
    // }

    // /**
    //  * ORMの使用方法2
    //  *
    //  * @Route("/find/{id}", name="find")
    //  */
    // public function find(Request $request, Person $person): Response
    // {
    //     // $formobj = new FindForm();
    //     // $form = $this->createFormBuilder($formobj)
    //     //     ->add('find', TextType::class)
    //     //     ->add('save', SubmitType::class, ['label' => 'Click'])
    //     //     ->getForm();

    //     // if ($request->getMethod() == 'POST') {
    //     //     $form->handleRequest($request);
    //     //     $finder = $form->getData()->getFind();
    //     //     $repository = $this->getDoctrine()
    //     //         ->getRepository(Person::class);
    //     //     $result = $repository->find($finder);
    //     // } else {
    //     //     $result = null;
    //     // }

    //     return $this->render('hello/find.html.twig', [
    //         'title' => 'Hello',
    //         // 'form' => $form->createView(),
    //         // 'data' => $result
    //         'data' => $person
    //     ]);
    // }

    // /**
    //  * CRUDの使用方法(CREATE)
    //  *
    //  * @Route("/create", name="create")
    //  */
    // public function create(Request $request): Response
    // {
    //     $person = new Person();
    //     $form = $this->createForm(PersonType::class, $person);
    //     // $form = $this->createFormBuilder()
    //     //     ->add('name', TextType::class)
    //     //     ->add('mail', TextType::class)
    //     //     ->add('age', IntegerType::class)
    //     //     ->add('save', SubmitType::class, ['label' => 'Click'])
    //     //     ->getForm();

    //     if ($request->getMethod() == 'POST') {
    //         $form->handleRequest($request);
    //         $person = $form->getData();
    //         // $data = $form->getData();
    //         // $person->setName($data['name']);
    //         // $person->setMail($data['mail']);
    //         // $person->setAge($data['age']);

    //         $manager = $this->getDoctrine()->getManager();
    //         $manager->persist($person);
    //         $manager->flush();
    //         return $this->redirect('/hello');
    //     } else {
    //         return $this->render('hello/create.html.twig', [
    //             'title' => 'Hello',
    //             'message' => 'Create Entity',
    //             'form' => $form->createView()
    //         ]);
    //     }
    // }

    // /**
    //  * CRUDの使用方法(UPDATE)
    //  *
    //  * @Route("/update/{id}", name="update")
    //  */
    // public function update(Request $request, Person $person): Response
    // {
    //     $form = $this->createFormBuilder($person)
    //         ->add('name', TextType::class)
    //         ->add('mail', TextType::class)
    //         ->add('age', IntegerType::class)
    //         ->add('save', SubmitType::class, ['label' => 'Click'])
    //         ->getForm();

    //     if ($request->getMethod() == 'POST') {
    //         $form->handleRequest($request);
    //         $person = $form->getData();

    //         $manager = $this->getDoctrine()->getManager();
    //         $manager->flush();
    //         return $this->redirect('/hello');
    //     } else {
    //         return $this->render('hello/create.html.twig', [
    //             'title' => 'Hello',
    //             'message' => 'Update Entity id = ' . $person->getId(),
    //             'form' => $form->createView()
    //         ]);
    //     }
    // }

    // /**
    //  * CRUDの使用方法(DELETE)
    //  *
    //  * @Route("/delete/{id}", name="delete")
    //  */
    // public function delete(Request $request, Person $person): Response
    // {
    //     $form = $this->createFormBuilder($person)
    //         ->add('name', TextType::class)
    //         ->add('mail', TextType::class)
    //         ->add('age', IntegerType::class)
    //         ->add('save', SubmitType::class, ['label' => 'Click'])
    //         ->getForm();

    //     if ($request->getMethod() == 'POST') {
    //         $form->handleRequest($request);
    //         $person = $form->getData();

    //         $manager = $this->getDoctrine()->getManager();
    //         $manager->remove($person);
    //         $manager->flush();
    //         return $this->redirect('/hello');
    //     } else {
    //         return $this->render('hello/create.html.twig', [
    //             'title' => 'Hello',
    //             'message' => 'Update Entity id = ' . $person->getId(),
    //             'form' => $form->createView()
    //         ]);
    //     }
    // }

    // /**
    //  * Twigの使用方法
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request): Response
    // {
    //     $data = [
    //         ['name' => 'Taro', 'age' => 37, 'mail' => 'taro@yamada'],
    //         ['name' => 'Hanako', 'age' => 29, 'mail' => 'hanako@yamada'],
    //         ['name' => 'Sashiko', 'age' => 43, 'mail' => 'sashiko@yamada'],
    //         ['name' => 'Jiro', 'age' => 18, 'mail' => 'jiro@yamada'],
    //     ];

    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'data' => $data
    //         // 'message' => 'これはサンプルテンプレート画面です。'
    //     ]);
    // }

    // /**
    //  * セッションの使用方法
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request, SessionInterface $session): Response
    // {
    //     $person = new MyData();

    //     $form = $this->createFormBuilder($person)
    //         ->add('data',  TextType::class)
    //         ->add('save', SubmitType::class, ['label' => 'Click'])
    //         ->getForm();

    //     if ($request->getMethod() == 'POST') {
    //         $form->handleRequest($request);
    //         $data = $form->getData();
    //         if ($data->getData() == '!') {
    //             $session->remove('data');
    //         } else {
    //             $session->set('data', $data->getData());
    //         }
    //     }

    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'data' => $session->get('data'),
    //         'form' => $form->createView()
    //     ]);
    // }

    // /**
    //  * Symfony Fomの使用方法2
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request): Response
    // {
    //     $person = new Person();
    //     $person->setName('taro')
    //         ->setAge(36)
    //         ->setMail('taro@yamada.kun');

    //     $form = $this->createFormBuilder($person)
    //         ->add('name',  TextType::class)
    //         ->add('age',  IntegerType::class)
    //         ->add('mail',  EmailType::class)
    //         ->add('save', SubmitType::class, ['label' => 'Click'])
    //         ->getForm();

    //     if ($request->getMethod() == 'POST') {
    //         $form->handleRequest($request);
    //         $obj = $form->getData();
    //         $msg = 'Name: ' . $obj->getName() . '<br>'
    //             . 'Age: ' . $obj->getAge() . '<br>'
    //             . 'Mail: ' . $obj->getMail();
    //     } else {
    //         $msg = 'お名前をどうぞ！';
    //     }

    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'message' => $msg,
    //         'form' => $form->createView()
    //     ]);
    // }

    // /**
    //  * Symfony Fomの使用方法1
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request): Response
    // {
    //     $form = $this->createFormBuilder()
    //         ->add('input',  TextType::class)
    //         ->add('save', SubmitType::class, ['label' => 'Click'])
    //         ->getForm();

    //     if ($request->getMethod() == 'POST') {
    //         $form->handleRequest($request);
    //         $msg = 'こんにちは、' . $form->get('input')->getData() . 'さん！';
    //     } else {
    //         $msg = 'お名前どうぞ！';
    //     }

    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'message' => $msg,
    //         'form' => $form->createView()
    //     ]);
    // }

    // /**
    //  * リクエスト取得方法3
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request): Response
    // {
    //     if ($request->getMethod() == 'POST') {
    //         $input = $request->request->get('input');
    //         $msg = 'こんにちは、' . $input . 'さん！';
    //     } else {
    //         $msg = 'お名前は？';
    //     }

    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'message' => $msg
    //     ]);
    // }

    // /**
    //  * リクエスト取得方法1
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request): Response
    // {
    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'message' => 'あなたのお名前'
    //     ]);
    // }

    // /**
    //  * リクエスト取得方法2
    //  *
    //  * @Route("/other", name="other")
    //  */
    // public function other(Request $request): Response
    // {
    //     $input = $request->request->get('input');
    //     $msg = 'こんにちは、' . $input . 'さん！';

    //     return $this->render('hello/index.html.twig', [
    //         'title' => 'Hello',
    //         'message' => $msg
    //     ]);
    // }

    // /**
    //  * リダイレクトでの変数受け渡し方法1
    //  *
    //  * @Route("/hello/{msg}", name="hello")
    //  */
    // public function index($msg='Hello!'): Response
    // {
    //     return $this->render('hello/index.html.twig', [
    //         'controller_name' => 'HelloController',
    //         'action' => 'index',
    //         'prev_action' => '(none)',
    //         'message' => $msg
    //     ]);
    // }

    // /**
    //  * リダイレクトでの変数受け渡し方法２
    //  *
    //  * @Route("/hello/{action}/{msg}", name="other")
    //  */
    // public function other($action, $msg): Response
    // {
    //     return $this->render('hello/index.html.twig', [
    //         'controller_name' => 'HelloController',
    //         'action' => 'other',
    //         'prev_action' => $action,
    //         'message' => $msg
    //     ]);
    // }

    // /**
    //  * JSONリダイレクト方法
    //  *
    //  * @Route("/hello", name="hello")
    //  */
    // public function index(Request $request, LoggerInterface $logger): Response
    // {
    //     return $this->render('hello/index.html.twig', [
    //         'controller_name' => 'HelloController',
    //     ]);

    //     $result = <<< EOM
    //         <html>
    //         <head><title>Hello</title></head>
    //         <body>
    //         <h1>Hello Symfony!</h1>
    //         <p>this is Symfony sample page.</p>
    //         </body>
    //         </html>
    //     EOM;

    //     $result = '<head><body>';
    //     $result .= '<h1>Subscribed Services</h1>';
    //     $result .= '<ol>';
    //     $arr = $this->getSubscribedServices();
    //     foreach ($arr as $key => $value) {
    //         $result .= '<li>' . $key . '</li>';
    //     }
    //     $result .= '</ol>';
    //     $result .= '</body></head>';

    //     $name = $request->get('name');
    //     $pass = $request->get('pass');

    //     $result = '<head><body>';
    //     $result .= '<h1>Parameter</h1>';
    //     $result .= '<p>name:' . $name . '</p>';
    //     $result .= '<p>pass:' . $pass . '</p>';
    //     $result .= '</body></head>';

    //     return new Response($result);

    //     $data = [
    //         'name' => [
    //             'first' => 'Taro',
    //             'second' => 'Yamada',
    //             'age' => 36,
    //             'mail' => 'taro@yamada.kun'
    //         ]
    //     ];

    //     $logger->info(serialize($data));

    //     return new JsonResponse($data);
    // }

    // /**
    //  * リダイレクト方法
    //  *
    //  * @Route("/other/{domain}", name="other")
    //  */
    // public function other(Request $request, $domain=''): Response
    // {
    //     if ($domain == '') {
    //         return $this->redirect('hello');
    //     } else {
    //         return new RedirectResponse("http://{$domain}.com");
    //     }
    // }
}

class FindForm
{
    private $find;

    public function getFind()
    {
        return $this->find;
    }

    public function setFind($find)
    {
        $this->find = $find;
    }
}

// class MyData
// {
//     protected $data = '';

//     public function getData()
//     {
//         return $this->data;
//     }

//     public function setData($data)
//     {
//         $this->data = $data;
//     }
// }

// class Person
// {
//     protected $name;
//     protected $age;
//     protected $mail;

//     public function getName()
//     {
//         return $this->name;
//     }

//     public function setName($name)
//     {
//         $this->name = $name;
//         return $this;
//     }

//     public function getAge()
//     {
//         return $this->age;
//     }

//     public function setAge($age)
//     {
//         $this->age = $age;
//         return $this;
//     }

//     public function getMail()
//     {
//         return $this->mail;
//     }

//     public function setMail($mail)
//     {
//         $this->mail = $mail;
//         return $this;
//     }
// }
