<?php
$obj=new Weixin();
  if(isset($_GET['echostr'])){
     $obj->join();
  }else{
     $obj->message();
  }
class Weixin
{
     public function join()
     {    
       $signature=$_GET['signature'];
         $timestamp=$_GET['timestamp'];
         $nonce=$_GET['nonce'];
         $echostr=$_GET['echostr'];
         $token='17802927036';
       $array=array($token,$timestamp,$nonce);
            sort($array,SORT_STRING);
            $str=implode($array);
             $newstr=sha1($str);
             if($newstr==$signature){
                 echo $echostr; 
             }
     }
     /*接受消息消息*/
     public function message()
     {
         $obj=$GLOBALS['HTTP_RAW_POST_DATA'];  //可以接受全局的数据 
       $postSql=simplexml_load_string($obj,'SimpleXMLElement',LIBXML_NOCDATA); //
          $message=trim($postSql->Content);
       if(strstr($message,'你')){ 
             $this->toMessage($postSql);  
       }
       elseif(strstr($message,'图片'))
           {
             $this->toImages($postSql);
           }else if($postSql->MsgType=='event')
           {
             $this->guanzhu($postSql);
           }
       
     }
    /*回复消息*/
      public function toMessage($postSql)
     {
           $xml="<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";
           
            $contentText='我在等待消息,我不知道你在问什么';
            $result=sprintf(
                        $xml,
                        $postSql->FromUserName,
                        $postSql->ToUserName,
                        time(),
                        'text',
                       // $contentText
                       $postSql->Content
                );//替换对应%S的内容
            
         echo $result;
     }
     public function toImages($postSql)
     {
         $xml="<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <ArticleCount>2</ArticleCount>
            <Articles>
            <item>
            <Title><![CDATA[%s]]></Title> 
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>
            <item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>
            </Articles>
            </xml>";
             
            $opnenid=$postSql->FromUserName;
            $weiid=$postSql->ToUserName;
            $time=time();
            $t='你好欢迎关注';
            $d='这个是描述';
            $PicUrl='https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1502532640212&di=27310a91ebddf85381bed4937c79e7d2&imgtype=jpg&src=http%3A%2F%2Fimg4.imgtn.bdimg.com%2Fit%2Fu%3D1848609946%2C3851033129%26fm%3D214%26gp%3D0.jpg';
            $PicUrl2='http://pic.qiantucdn.com/58pic/22/06/55/57b2d9a265f53_1024.jpg';
            $Url='http://www.qq.com/';
            $Url2='http://www.baidu.com/';
            $result=sprintf($xml,$opnenid,$weiid,$time,$t,$d,$PicUrl,$Url,$t,$d,$PicUrl2,$Url2);
            echo $result;
            $this->log($result);
     }
       
  public function guanzhu ($postSql)
  {
       $xml="<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";
            $contentText='who are you ?欢迎光临我的公众号';
            $result=sprintf(
                        $xml,
                        $postSql->FromUserName,
                        $postSql->ToUserName,
                        time(),
                        'text',
                        $contentText
                );//替换对应%S的内容
            
         echo $result;
     }
      public function log($data='')
    {
        $res=fopen('dushaokun.txt','w');
        file_put_contents('dushaokun.txt', $data);
        fclose($res);
    }
  

  }
