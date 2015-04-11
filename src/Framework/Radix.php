<?php

namespace Radix\Framework;

use Silex\Application as SilexApplication;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use LinkORB\Component\DatabaseManager\DatabaseManager;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Symfony\Component\Yaml\Parser as YamlParser;

use Radix\Framework\Interfaces\AppInterface;
use Radix\Framework\Interfaces\RadixInterface;
use Radix\Framework\Model\Type;
use Radix\Framework\Model\Field;
use ReflectionClass;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use RuntimeException;

class Radix extends SilexApplication implements RadixInterface
{
    private $annotationReader;
    public function __construct(array $values = array())
    {
        parent::__construct($values);
        
        
        AnnotationRegistry::registerAutoloadNamespace(
            "Symfony\\Component\\Routing\\Annotation",
            __DIR__ . '/../../vendor/symfony/routing'
        );

        //AnnotationRegistry::registerAutoloadNamespace("Radix\\Annotations", __DIR__ . "");
        //PSR-4 doesn't seem to work in registerAutoloadNamespace, so explicitly using registerFile
        AnnotationRegistry::registerFile(__DIR__ . "/Annotation/App.php");
        AnnotationRegistry::registerFile(__DIR__ . "/Annotation/Type.php");
        AnnotationRegistry::registerFile(__DIR__ . "/Annotation/Field.php");
        
        $this->annotationReader = new AnnotationReader();
    
        $this->loadConfig();
        $this->configureRoutes();
        $this->configureProviders();
        $this->configureRepositories();
        $this->configureServices();
        //$this->configureSecurity();
        $this->configureListeners();
    }
    
    public function run(Request $request = null)
    {
        $this['twig']->addGlobal('apps', $this->apps);
        $this['twig']->addGlobal('title', $this['title']);

        parent::run($request);
    }
    
    private $apps = array();
    
    public function getApps()
    {
        return $this->apps;
    }
    
    public function registerApp(AppInterface $app)
    {
        // Resolve basepath of passed app instance
        $classname = get_class($app);
        $rc = new ReflectionClass($classname);
        $annotations = $this->annotationReader->getClassAnnotations($rc);
        foreach ($annotations as $annotation) {
            $app->setName($annotation->getName());
            $app->setTitle($annotation->getTitle());
            $app->setDescription($annotation->getDescription());
        }
        
        $basedir = dirname($rc->getFilename());
        
        $namespace = $rc->getNamespaceName();
        $this->registerControllers($basedir . '/Controller', $namespace . '\Controller');
        $this->registerTypes($basedir . '/Type', $namespace . '\Type');
        
        $this->apps[] = $app;
        $app->register($this);
    }
    
    private function registerControllers($basedir, $namespace)
    {
        foreach (glob($basedir . "/*.php") as $filename) {
            $classname = $namespace . '\\' . str_replace('.php', '', basename($filename));

            $rc = new ReflectionClass($classname);
            $methods = $rc->getMethods();
            foreach ($methods as $method) {

                $annotations = $this->annotationReader->getMethodAnnotations($method);

                foreach ($annotations as $annotation) {
                    if ($annotation instanceof \Symfony\Component\Routing\Annotation\Route) {
                        $data = array();
                        $controller = $classname . '::' . $method->getName();

                        $this->match(
                            $annotation->getPath(),
                            $controller
                        )->bind(
                            $annotation->getName()
                        );
                    }
                }

            }
        }
    }

    private function registerTypes($basedir, $namespace)
    {
        foreach (glob($basedir . "/*.php") as $filename) {
            $classname = $namespace . '\\' . str_replace('.php', '', basename($filename));

            $rc = new ReflectionClass($classname);
            $annotations = $this->annotationReader->getClassAnnotations($rc, '\Radix\Framework\Annotation\Type');
            $type = new Type();
            foreach ($annotations as $annotation) {
                $type->setName($annotation->getName());
                $type->setDescription($annotation->getDescription());
            }
            


            $properties = $rc->getProperties();
            foreach ($properties as $property) {
                $field = new Field();

                $annotations = $this->annotationReader->getPropertyAnnotations($property, '\Radix\Framework\Annotation\Field');
                foreach ($annotations as $annotation) {
                    $field->setName($annotation->getName());
                    $field->setType($annotation->getType());
                    $field->setDescription($annotation->getDescription());
                    $type->addField($field);
                }
            }
        }
        $this->registerType($type);
    }
    
    private $types = array();
    
    public function registerType(Type $type)
    {
        $this->types[$type->getName()] = $type;
    }
    
    public function getTypes()
    {
        return $this->types;
    }

    
    private function loadConfig()
    {
        // Setup defaults
        $this['title'] = "Radix";
        $this['debug'] = true;
        
        // Load configfile
        $parser = new YamlParser();
        $filename = __DIR__.'/../../radix.yml';
        if (!file_exists($filename)) {
            // Used from vendor ?
            $filename = __DIR__.'/../../../../radix.yml';
        }
        if (!file_exists($filename)) {
            throw new RuntimeException("radix.yml not found. Please copy radix.yml.dist and adjust for your installation.");
        }
        
        $config = $parser->parse(file_get_contents($filename));
        //print_r($config); exit();

        $this['title'] = $config['title'];
        
        foreach ($config['apps'] as $classname) {
            $app = new $classname();
            $this->registerApp($app);
        }
        //exit("QRRRR");
        
        /*
        $dbname = 'radix';

        $manager = new DatabaseManager();
        $dbal = $manager->getDbalConnection($dbname, 'default');
        $this['radix.dbal'] = $dbal;
        $this['radix.baseurl'] = 'http://localhost:9999/';
        */
    }
    
    private function configureRoutes()
    {
        $this->get(
            '/',
            'Radix\Framework\Controller\FrameworkController::indexAction'
        );
        $this->get(
            '/radix/info',
            'Radix\Framework\Controller\FrameworkController::radixInfoAction'
        );
    }
    
    private function configureProviders()
    {
        // *** Setup Translation ***
        
        /*
        $this->register(new \Silex\Provider\LocaleServiceProvider());
        $this->register(new \Silex\Provider\ValidatorServiceProvider());
        $this->register(new \Silex\Provider\TranslationServiceProvider(), array(
            'translator.messages' => array(),
        ));
        */
        // *** Setup Form ***
        
        // $this->register(new \Silex\Provider\FormServiceProvider());

        // *** Setup Routing ***
        $this->register(new \Silex\Provider\RoutingServiceProvider());
        
        // *** Setup Twig ***
        $this->register(new \Silex\Provider\TwigServiceProvider());
        
        $options = array();
        $loader = null; // TODO
        $twig = new \Twig_Environment($loader, $options);
                
        $this['twig.loader.filesystem']->addPath(__DIR__ . '/../App/Example/Views', 'ExampleApp');
        $this['twig.loader.filesystem']->addPath(__DIR__ . '/Views', 'Framework');
        $this['twig.loader.filesystem']->addPath(__DIR__ . '/../../themes/default', 'Theme');
        
        // *** Setup Sessions ***
        /*
        $this->register(new \Silex\Provider\SessionServiceProvider(), array(
            'session.storage.save_path' => '/tmp/frontcontroller_sessions'
        ));
        */


        // *** Setup Doctrine DBAL ***
        
        /*
        $this->register(new \Silex\Provider\DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver'   => 'pdo_mysql',
                    'host'      => $this['db.config.server'],
                    'dbname'    => $this['db.config.name'],
                    'user'      => $this['db.config.username'],
                    'password'  => $this['db.config.password'],
                    'charset'   => 'utf8',
            ),
        ));
        */
    }
    
    private function configureRepositories()
    {
        /*
        $this['radix.repository.user'] = new UserRepository($this['radix.dbal']);
        $this['radix.repository.group'] = new GroupRepository($this['radix.dbal']);
        */
    }
    
    private function configureServices()
    {
        
    }
    
    private function configureSecurity()
    {
        $this->register(new \Silex\Provider\SecurityServiceProvider(), array());
        
        //$this['security.encoder.digest'] = new PlaintextPasswordEncoder(true);

        $this['security.firewalls'] = array();

        /*
        $this['security.firewalls'] = array(
            'admin' => array(
                'stateless' => true,
                'pattern' => '^/admin',
                'http' => true,
                'users' => $this['radix.repository.user'],
            )
        );
        */
    }
    
    private function configureListeners()
    {
        // todo
    }
}
