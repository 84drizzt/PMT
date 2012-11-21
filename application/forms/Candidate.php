<?php

class Form_Candidate extends Zend_Dojo_Form
{

    public function init()
    {
        $id = new Zend_Form_Element_Hidden("id");
        $id->setValue(0);
        $id->setRequired(true);
        $idValidator = new Zend_Validate_Int();
        $id->addValidator($idValidator);
        $this->addElement($id, "id");
        
        
        $c_name = new Zend_Dojo_Form_Element_TextBox("c_name");
        $c_name->setLabel("中文名: ");
        $c_name->setRequired(true);
        $this->addElement($c_name, "c_name");
        
        $e_name = new Zend_Dojo_Form_Element_TextBox("e_name");
        $e_name->setLabel("英文名: ");
        $e_name->setRequired(true);
        $nameValidator = new Zend_Validate_Alpha(true);
        $e_name->addValidator($nameValidator);
        $this->addElement($e_name, "e_name");
        
        
        $gender = new Zend_Dojo_Form_Element_RadioButton("gender");
        $gender->setLabel("性别: ");
        $gender->setRequired(true);
        $gender->addMultiOption("M","男")
        		->addMultiOption("F","女")
        		->setValue("M")
        		->setSeparator(" ");
        		
        		
        $this->addElement($gender, "gender");
        
        
        $birth = new Zend_Dojo_Form_Element_DateTextBox("birth");
        $birth->setLabel("生日: ");
        
        $this->addElement($birth, "birth");
        
        $join_date = new Zend_Dojo_Form_Element_DateTextBox("join_date");
        $join_date->setLabel("加入日期: ");
        $this->addElement($join_date, "join_date");
        
        
        
        $c_resume = new Zend_Form_Element_Textarea("c_resume");
        $c_resume->setLabel("中文简历: ");
        $this->addElement($c_resume, "c_resume");
        
        $e_resume = new Zend_Form_Element_Textarea("e_resume");
        $e_resume->setLabel("英文简历: ");
        $this->addElement($e_resume, "e_resume");
        
        
        
        
        
        $photo = new Zend_Form_Element_File("photo");
        $photo->setLabel("Photo: ")->setRequired(true)
        	->setDestination(APPLICATION_PATH . "/../public/images/artists_avar/");
        	
        $photoFilter = new Zend_Filter_File_Rename(md5(microtime()) . ".jpg");
        
        $photo->addFilter($photoFilter);
        	
        $photoValidator = new Zend_Validate_File_Extension("jpg");
        $photo->addValidator($photoValidator);
        $this->addElement($photo, "photo");
        
        $is_recommand = new Zend_Form_Element_Radio("is_recommand");
        $is_recommand->setLabel("是否推荐: ");
        $is_recommand->setRequired(true);
        $is_recommand->addMultiOption("1","是")
        		->addMultiOption("0","不")
        		->setValue("0")
        		->setSeparator(" ");
        		
        		
        $this->addElement($is_recommand, "is_recommand");
        
//        $display_at_en = new Zend_Form_Element_Radio("display_at_en");
//        $display_at_en->setLabel("显示在英文站: ");
//        $display_at_en->setRequired(true);
//        $display_at_en->addMultiOption("1","是")
//        		->addMultiOption("0","不")
//        		->setValue("1")
//        		->setSeparator(" ");
//        		
//        		
//        $this->addElement($display_at_en, "display_at_en");
//        
//        $display_at_cn = new Zend_Form_Element_Radio("display_at_cn");
//        $display_at_cn->setLabel("显示在中文站: ");
//        $display_at_cn->setRequired(true);
//        $display_at_cn->addMultiOption("1","是")
//        		->addMultiOption("0","不")
//        		->setValue("1")
//        		->setSeparator(" ");
//        		
//        		
//        $this->addElement($display_at_cn, "display_at_cn");
        
        $submit = new Zend_Form_Element_Submit("submit");
        $this->addElement($submit, "submit");
    }


}

