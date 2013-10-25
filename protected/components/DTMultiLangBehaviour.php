<?php

/**
 * Language tranlsation 
 * libraray
 */
class DTMultiLangBehaviour extends CActiveRecordBehavior {

    /**
     * Named scope to use {@link localizedRelation}
     * @param string $lang the lang the retrieved models will be translated (default to current application language)
     * @return model 
     */
    public $defaultLanguage = 'en';
    public $current_lang;
    public $relation;
    public $langClassName;
    public $langTableName;
    public $langForeignKey;
    public $localizedAttributes = array();
    public $localizedPrefix;
    public $languages;

    public function localized($lang = 'en') {

        $this->current_lang = $lang;

        $owner = $this->getOwner();

        if ($this->current_lang != $this->defaultLanguage) {
            $owner->getDbCriteria()->with = array($this->relation => array('joinType' => 'LEFT JOIN',
                    "condition" => "lang_id='$lang'"));
        }

        return $owner;
    }

    public function afterFind($event) {
        $owner = $this->getOwner();
        /*
         * empty variable then replace 
         */
        if(empty($this->current_lang)){
            $this->current_lang = Yii::app()->controller->currentLang;
        }
    
        if (isset($this->current_lang) && $this->current_lang != $this->defaultLanguage) {
            $relation = $owner->getRelated($this->relation);
           
            foreach ($this->localizedAttributes as $attr) {
                $owner->$attr = isset($relation[0]->$attr) ? $relation[0]->$attr : "";
            }
        }


        return parent::afterFind($event);
    }

    /**
     * every time when english or parent records
     * is created then 
     * afterSave will be called
     * to save in other languages
     * @param type $event
     */
    public function afterSave($event) {

        $owner = $this->getOwner();
        if ($owner->isNewRecord) {
            $languages = Yii::app()->params['otherLanguages'];
            foreach ($languages as $lang) {
                $chilModel = new $this->langClassName;
                $chilModel->lang_id = $lang;
                foreach ($this->localizedAttributes as $attr) {
                    $chilModel->$attr = "";
                }
                $foreign_key = $this->langForeignKey;
                $chilModel->$foreign_key = $owner->primaryKey;

                $chilModel->save();
            }
        }

        return parent::afterSave($event);
    }

}

?>