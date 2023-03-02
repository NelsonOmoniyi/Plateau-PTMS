<?php 
include("../libs/dbfunctions.php");
Class PaymentCategory extends dbobject{
    public function category_list($data){
        $table_name    = "payment_category";
		$primary_key   = "id";
		$columner = array(
			array( 'db' => 'id', 'dt' => 0 ),
			array( 'db' => 'item',  'dt' => 1 ),
			array( 'db' => 'code',  'dt' => 2 ),
			array( 'db' => 'amount',  'dt' => 3 ),
			array( 'db' => 'link',  'dt' => 4 ),
			array( 'db' => 'id',  'dt' => 5,'formatter' => function( $d,$row ) {

						return '<a class="btn btn-primary btn-sm" onclick="getModal(\'setup/payt_category_setup.php?op=edit&id='.$d.'\',\'modal_div\')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Edit Menu</a> <a class="btn btn-danger btn-sm" onclick="deletePaytCat(\''.$d.'\')"  href="javascript:void(0)" >Delete Menu</a>';
                        
					} )
			);
           
		$filter = "";
		$datatableEngine = new engine();
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }

    public function create($data){
        $table = "payment_category";
        
        if($data['operation'] == "new"){
            $validation = $this->validate($data,
                array(
                    'item'=>'required',
                    'code'=>'required',
                    'amount'=>'required',
                    'link'=>'required'
                ),
                array('item'=>'Item','code'=>'Short Code','amount'=>'Amount','link'=>'Link')
            );
            
            $data['id'] = $this->getnextidpaytcat($table);
            if(!$validation['error']){
                $count = $this->doInsert('payment_category',$data, array('op','operation'));
                if($count > 0){
                    return json_encode(array('response_code'=>200, 'response_message'=>'Payment category created successfully')); 
                }else{
                    return json_encode(array('response_code'=>201, 'response_message'=>'could not create payment category'));
                }
            }else{

                return json_encode(array('response_code' => 300, 'response_message' => $validation['messages'][0]));
            }
        } else if($data['operation'] == "edit"){
            $count = $this->doUpdate('payment_category',$data, array('op','operation'), array('id' => $data['id']));
            if($count > 0){
                return json_encode(array('response_code'=>200, 'response_message'=>'Payment category updated successfully')); 
            }else{
                return json_encode(array('response_code'=>201, 'response_message'=>'could not update payment category'));
            }
        }
    }

    public function delete($data){
        $id = $data['id'];
        $sql     = "DELETE FROM payment_category WHERE id = '$id'";
        $query_ = $this->db_query( $sql, false );
        
                if($query_ > 0){
                    return json_encode(array('response_code'=>200, 'response_message'=>'Payment category deleted')); 
                }else{
                    return json_encode(array('response_code'=>201, 'response_message'=>'could not delete payment category'));
                }
    }
}