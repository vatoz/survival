<?php 
namespace FastVip;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class FastPort implements MessageComponentInterface{
private $file;
protected $clients;

public function __construct(){
	
	$this->clients= new \SplObjectStorage;
	}
public function onOpen(ConnectionInterface $conn){
	$this->clients->attach($conn);
	}
public function onMessage(ConnectionInterface $from,$msg){
	foreach($this->clients as $client){
		if($from !==$client){
			//$client->send($msg);
			}
			
		
			$client->send($this->read_line_arduino());
		}
	
	}
public function onClose(ConnectionInterface $conn){
	$this->clients->detach($conn);
	//fclose($this->file);
	}
public function onError(ConnectionInterface $conn,\Exception $e){
	$conn->close();
	}
private function read_line_arduino(){
	$Row="";
	$data=-1;
	$this->file= fopen("/dev/ttyUSB0","r");
	while($data!="{"){
		$data=fgetc($this->file);	
	}
	$data=fgetc($this->file);	
	while($data!="}"){
		$Row.=$data;
		$data=fgetc($this->file);	
	}
	
	return $Row;
	
	
	}


}
