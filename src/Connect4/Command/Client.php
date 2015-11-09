<?php
namespace Connect4\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Connect4\Client\BrainFactory;
use Connect4\Client\Client as GameClient;

class Client extends Command
{
    /**
     * @var BrainFactory
     */
    private $brainFactory;
    
    /**
     * @param BrainFactory $brainFactory
     */
    public function __construct(BrainFactory $brainFactory = null)
    {
       $this->brainFactory = $brainFactory ?: new BrainFactory();
       parent::__construct();
    }
    
    protected function configure()
    {
        $this->setName('connect4:client')
             ->setDescription('Run a Connect4 Client')
            ->setDefinition(new InputDefinition(array(
                new InputArgument('brain', InputArgument::REQUIRED),
                new InputArgument('server', InputArgument::REQUIRED))));
    }
    
    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null|void   Script exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $brainName = $input->getArgument('brain');
        $host = $input->getArgument('server');
        
        $brain = $this->brainFactory->build($brainName);
        $responseInterpreter = new \Connect4\Client\ResponseInterpreter();
        $client = new \Connect4\Client\Client($brain);
        $loop = \React\EventLoop\Factory::create();

        $dnsResolverFactory = new \React\Dns\Resolver\Factory();
        $dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

        $connector = new \React\SocketClient\Connector($loop, $dns);

        $connector->create($host, 1337)->then(function (\React\Stream\Stream $stream) use ($brain, $responseInterpreter, $client) {
            $stream->write($brain->getName());
            $stream->on('data', function($data) use ($stream, $client, $responseInterpreter){
               if (! $data) {
                   die ('CONNECTION TERMINATED');
               }
               $response = $responseInterpreter->buildResponse($data);
               $move = $client->handle($response);
               if ($move) {
                   $stream->write($move->getColumn()->getValue());
               }
            });
        });

        $loop->run();
    }
}