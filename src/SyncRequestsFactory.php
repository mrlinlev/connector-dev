<?php

namespace Leveon\Connector;

use Leveon\Connector\Models\AModel;

class SyncRequestsFactory{
	
	#prop catalog vgS
	private $catalog;
	#prop catalogPath vgS
	private $catalogPath;
	#prop basePath vgS
	private $basePath;
	#prop conn vgS
	private $conn;
	#prop db vgS
	private $db;
	
	public function __construct($catalog){
		$this->catalog = (int)$catalog;
		$this->conn = new Connector();
		$this->basePath = '/api';
		$this->catalogPath = "{$this->basePath}/catalog/{$catalog}";
		$this->db = new SqliteManager();
	}
	
	private static function succ($resp){
		return intdiv($resp->getResponseCode(), 100)===2;
	}
	
	public function syncPrices($packer){
		foreach(['product', 'offer'] as $type){
			$send = $packer->toJSON(['type' => $type, 'delete' => true]);
			if($send!==null){
				$c = $this->conn;
				$resp = $c->process(
					$c->delete("{$this->catalogPath}/{$type}/prices", $send)
				);
				if(!self::succ($resp)) return false;
			}
			$send = $packer->toJSON(['type' => $type, 'delete' => false]);
			if($send!==null){
				$c = $this->conn;
				$resp = $c->process(
					$c->patch("{$this->catalogPath}/{$type}/prices", $send)
				);
				if(!self::succ($resp)) return false;
			}
		}
		return true;
	}
		
	public function syncAmounts($packer){
		foreach(['product', 'offer'] as $type){
			$send = $packer->toJSON(['type' => $type, 'delete' => true]);
			if($send!==null){
				$c = $this->conn;
				$resp = $c->process(
					$c->delete("{$this->catalogPath}/{$type}/amounts", $send)
				);
				if(!self::succ($resp)) return false;
			}
			$send = $packer->toJSON(['type' => $type, 'delete' => false]);
			if($send!==null){
				$c = $this->conn;
				$resp = $c->process(
					$c->patch("{$this->catalogPath}/{$type}/amounts", $send)
				);
				if(!self::succ($resp)) return false;
			}
		}
		return true;
	}
		
	public function syncRelations($packer){
		$send = $packer->toJSON(['old']);
		if($send!==null){
			$c = $this->conn;
			$resp = $c->process(
				$c->delete("{$this->catalogPath}/product-relations", $send)
			);
			if(intdiv($resp->getResponseCode(), 100)!==2) return false;
		}
		$send = $packer->toJSON(['new']);
		if($send!==null){
			$c = $this->conn;
			$resp = $c->process(
				$c->post("{$this->catalogPath}/product-relations", $send)
			);
			if(intdiv($resp->getResponseCode(), 100)!==2) return false;
		}
		return true;
	}
	
	public function createInstanceIfNotExists($type, $path, $localId, $model){
		$outer = $this->db->outerByLocal($type, $localId);
		if($outer===null){
			$c = $this->conn;
			$send = $model instanceof AModel? $model->toJSON(): $model;
	    #echo json_encode($send, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
			$resp = $c->process(
				$c->post("{$this->catalogPath}/{$path}", $send)
			);
			if($resp->getResponseCode()===200 || $resp->getResponseCode()===201){
				$outerId = json_decode($resp->getResponse())->id;
				var_dump($outerId);
				var_dump($resp->getResponse());
				$this->db->bind($type, $localId, $outerId);
			}
			return $resp->getResponseCode()>199 && $resp->getResponseCode()<300;
		}else{
			return false;
		}
	}
	
	public function createFullInstanceIfNotExists($type, $path, $localId, $model){
		$outer = $this->db->outerByLocal($type, $localId);
		if($outer===null){
			$c = $this->conn;
			$send = $model instanceof AModel? $model->toJSON(): $model;
	    #echo json_encode($send, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
			$resp = $c->process(
				$c->post("{$this->catalogPath}/{$path}/model", $send)
			);
			if($resp->getResponseCode()===200 || $resp->getResponseCode()===201){
				$outerId = json_decode($resp->getResponse())->id;
				var_dump($outerId);
				#var_dump($resp->getResponse());
				$this->db->bind($type, $localId, $outerId);
			}
			return $resp->getResponseCode()>199 && $resp->getResponseCode()<300;
		}else{
			return false;
		}
	}
	
	public function upInstance($type, $path, $localId, $model){
		$c = $this->conn;
		$outer = $this->db->outerByLocal($type, $localId);
		$send = $model instanceof AModel? $model->toJSON(): $model;
		$resp = null;
		if($outer!==null){
			$resp = $c->process(
				$c->patch("{$this->catalogPath}/{$path}/{$outer}", $send)
			);
		}else{
			$resp = $c->process(
				$c->post("{$this->catalogPath}/{$path}", $send)
			);
			if($resp->getResponseCode()===200 || $resp->getResponseCode()===201){
				$outerId = json_decode($resp->getResponse())->id;
				$this->db->bind($type, $localId, $outerId);
			}
		}
		return $resp->getResponseCode()>199 && $resp->getResponseCode()<300;
	}
	
	public function upFullInstance($type, $path, $localId, $model){
		$c = $this->conn;
		$outer = $this->db->outerByLocal($type, $localId);
		$send = $model instanceof AModel? $model->toJSON(): $model;
	  #echo json_encode($send, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
		$resp = null;
		if($outer!==null){
			$resp = $c->process(
				$c->patch("{$this->catalogPath}/{$path}/{$outer}/model", $send)
			);
		}else{
			$resp = $c->process(
				$c->post("{$this->catalogPath}/{$path}/model", $send)
			);
			if($resp->getResponseCode()===200 || $resp->getResponseCode()===201){
				$outerId = json_decode($resp->getResponse())->id;
				$this->db->bind($type, $localId, $outerId);
			}
		}
		return $resp->getResponseCode()>199 && $resp->getResponseCode()<300;
	}
	
	public function delInstance($type, $path, $localId){
		$outer = $this->db->outerByLocal($type, $localId);
		if($outer===null) return true;
		$c = $this->conn;
		$resp = $c->process(
			$c->delete("{$this->catalogPath}/{$path}/{$outer}")
		);
		if(intdiv($resp->getResponseCode(), 100)===2 || $resp->getResponseCode()===404){
			$this->db->unbind($type, $localId);
		}
		return $resp->getResponseCode()>199 && $resp->getResponseCode()<300;
	}
	
	public function delInstanceOuter($type, $path, $outer){
		$c = $this->conn;
		$resp = $c->process(
			$c->delete("{$this->catalogPath}/{$path}/{$outer}")
		);
		if(intdiv($resp->getResponseCode(), 100)===2 || $resp->getResponseCode()===404){
			$this->db->unbindOuter($type, $outer);
		}
		return $resp->getResponseCode()>199 && $resp->getResponseCode()<300;
	}
	
	public function delAllInstances($type, $path){
		$list = $this->db->all($type);
		foreach($list as $item){
			$this->delInstanceOuter($type, $path, $item['outer']);
		}
	}
	
	public function upBrand($localId, $model){
		return $this->upFullInstance('brand', 'brand', $localId, $model);
	}
	
	public function createBrandIfNotExists($localId, $model){
		return $this->createFullInstanceIfNotExists('brand', 'brand', $localId, $model);
	}
	
	public function delBrand($localId){
		return $this->delInstance('brand', 'brand', $localId);
	}
	
	public function delAllBrands(){
		return $this->delAllInstances('brand', 'brand');
	}
	
	public function createPropertyIfNotExists($localId, $model){
		return $this->createInstanceIfNotExists('property', 'properties', $localId, $model);
	}
	
	public function upProperty($localId, $model){
		return $this->upInstance('property', 'properties', $localId, $model);
	}
	
	public function delProperty($localId){
		return $this->delInstance('property', 'properties', $localId);
	}
	
	public function createProductTypeIfNotExists($localId, $model){
		return $this->createFullInstanceIfNotExists('type', 'type', $localId, $model);
	}
	
	public function upProductType($localId, $model){
		return $this->upFullInstance('type', 'type', $localId, $model);
	}
	
	public function delProductType($localId){
		return $this->delInstance('type', 'type', $localId);
	}
	
	public function createCollectionIfNotExists($localBrandId, $localId, $model){
		$outerBrandId = $this->db->outerByLocal('brand', $localBrandId);
		return $this->createFullInstanceIfNotExists('collection', "brand/{$outerBrandId}/collection", $localId, $model);
	}
	
	public function upCollection($localBrandId, $localId, $model){
		$outerBrandId = $this->db->outerByLocal('brand', $localBrandId);
		return $this->upFullInstance('collection', "brand/{$outerBrandId}/collection", $localId, $model);
	}
	
	public function delCollection($localBrandId, $localId){
		$outerBrandId = $this->db->outerByLocal('brand', $localBrandId);
		return $this->delInstance('collection', "brand/{$outerBrandId}/collection", $localId);
	}
	
	public function createProductIfNotExists($localId, $model){
		return $this->createFullInstanceIfNotExists('product', 'products', $localId, $model);
	}
	
	public function upProduct($localId, $model){
		return $this->upFullInstance('product', 'products', $localId, $model);
	}
	
	public function delProduct($localId){
		return $this->delInstance('product', 'products', $localId);
	}
	
	public function delAllProducts(){
		return $this->delAllInstances('product', 'products');
	}
	
	public function createOfferIfNotExists($localId, $model){
		return $this->createInstanceIfNotExists('offer', 'offer', $localId, $model);
	}
	
	public function upOffer($localId, $model){
		return $this->upInstance('offer', 'offer', $localId, $model);
	}
	
	public function delOffer($localId){
		return $this->delInstance('offer', 'offer', $localId);
	}
	
	public function finish(){
		$this->db->close();
	}
	
	#gen - begin
	public function getCatalog(){ return $this->catalog; }
	public function getCatalogPath(){ return $this->catalogPath; }
	public function getBasePath(){ return $this->basePath; }
	public function getConn(){ return $this->conn; }
	public function getDb(){ return $this->db; }

	#gen - end
}