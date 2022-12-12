<?php
class Dashboard extends dbobject
{
    public function topFiveChurches($data)
    {
       if($_SESSION['role_id_sess'] != 001 )
       {
           $filter = " AND source_acct = '$_SESSION[church_id_sess]'";
       }
       
       $sql    = "SELECT SUM(transaction_amount) AS amount FROM transaction_table WHERE response_code = '99' $filter ";
       $result = $this->db_query($sql);
       $total_sum = $result[0]['amount'];
       
       $sql    = "SELECT SUM(transaction_amount) AS amount,source_acct FROM transaction_table WHERE response_code = '99' $filter GROUP BY source_acct LIMIT 5";
       $result = $this->db_query($sql);
       $church_arr = array();
       $church_contribution = array();
       $color_list = array('#47bac1','#fcc100','#f44455','#E8EAED');
       $colors = array();
       $top_five = array();
       $count = 0;
       foreach($result as $row)
       {
           $church_name = $this->getitemlabel("church_table","church_id",$row['source_acct'],"church_name");
           $church_name = (strlen($church_name) > 11)?substr($church_name,0,11)."...":$church_name;
           $colors[] = $color_list[$count];
           $church_arr[] = $church_name;
           $church_contribution[] = ceil(($row['amount']/$total_sum) * 360);
           $count++;
           $top_five['church_name'][]   = $church_name;
           $top_five['church_amount'][] = $row['amount'];
       }
       $data   = array('type'=>'pie','data'=>array('labels'=>$church_arr,
                     'datasets'=>array(
                                    array('data'=>$church_contribution,
                                          'backgroundColor'=>$colors,
                                          'borderColor'=>'transparent'
                                         )
                                )
                            )
                    );
       $html = "";
       for($x=0; $x<=count($top_five['church_name']); $x++)
       {
           $html = $html."<tr>";
           $html = $html."<td>".$top_five['church_name'][$x]."</td>";
            $html = $html."<td>".$top_five['church_amount'][$x]."</td>";
           $html = $html."</tr>";
       }
       
       return json_encode(array('pie'=>$data,'topfive'=>$html));

    }
    public function transactions()
    {
        $sql = "SELECT * FROM transaction_table WHERE response_code = '0'";
        $this->db_query($sql);
        
    }
    public function carousel($data)
    {
        $owl = array();
        if($_SESSION['role_id_sess'] == "001" || $_SESSION['role_id_sess'] == "005")
        {
            $filter = "";
        }
        else
        {
            $filter = " AND source_acct = '$_SESSSION[church_id_sess]'";
        }
        $sql = "SELECT SUM(transaction_amount) AS amount,source_acct FROM transaction_table WHERE 1 = 1 $filter GROUP BY source_acct ";
        $result  = $this->db_query($sql);
//        $sql2 = "SELECT SUM(transaction_amount) AS amount,church_id FROM transaction_table GROUP BY church_id ";
        foreach($result as $row)
        {
            $owl[] = array("item"=>'<div class="col-12 col-sm-6 col-xl d-flex">
							<div class="card flex-fill mb-0">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-1">
											<i class="fa fa-church text-success" style="font-size:35px" ></i>
										</div>
										<div class="media-body">
											<h6 class="mb-2">'.$this->getitemlabel('church_table','church_id',$row[source_acct],'church_name').'</h6>
                                            <div class="row mb-0"">
                                                <div class="col-sm-12">
                                                    <b style="color:red">Posted:</b> &#8358;'.number_format($row[amount],2).'
                                                </div>
                                            </div>
										</div>
									</div>
								</div>
							</div>
						</div>');
        }
        
        $res = array("owl"=>$owl);
        return json_encode($res);
    }
    public function remittance($data)
    {
        
        $filter = ($_SESSION['role_id_sess'] == 001)?"":" AND church_id = '$_SESSION[church_id_sess]'";
        $sql    = "SELECT SUM(transaction_amount) AS amount, MONTH(created) AS trans_month,response_code  FROM transaction_table WHERE 1 = 1 AND YEAR(created) = YEAR(CURDATE()) $filter GROUP BY MONTH(created)";
        $result = $this->db_query($sql);
        $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        $data   = array();
        $data_months = array();
        $data_amount = array();
        foreach($result as $row)
        {
            $index = $row['trans_month'] - 1;
            $data_months[] = $months[$index];
            
                $data_amount[] = $row['amount'];
            
            
        }
        $data = array('type'=>'line','data'=>array(
                                        'labels'=>$data_months,
                                        'datasets'=>array(
                                            array('label'=>'Paid (NGN)',
                                                  'fill'=>true,
                                                  'backgroundColor'=>'transparent',
                                                  'borderColor'=>'#47bac1',
                                                  'data'=>$data_amount
                                                 )
                                            )
                                        ), 'options'=>array('maintainAspectRatio'=>false,'legend'=>array('display'=>false),'tooltips'=>array('intersect'=>false),'hover'=>array('intersect'=>true),'plugins'=>array('filter'=>array('propagate'=>false)),'scales'=>array('xAxes'=>array(array('reverse'=>true,'gridLines'=>array('color'=>'rgba(0,0,0,0.05)'))),'yAxes'=>array('ticks'=>array('stepSize'=>500),'display'=>true,'borderDash'=>array(5,5),'gridLines'=>array('color'=>'rgba(0,0,0,0)','fontColor'=>'#fff'))))
                     );
        return json_encode($data);
    }
}