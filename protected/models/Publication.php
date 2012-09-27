<?php

/**
 * This is the model class for table "tbl_publication".
 *
 * The followings are the available columns in table 'tbl_publication':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $create_time
 * @property integer $update_time
 * @property string $atcontent_embed_code
 * @property string $atcontent_publication_id
 * @property integer $author_id
 *
 * The followings are the available model relations:
 * @property User $author
 */
class Publication extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Publication the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_publication';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('author_id', 'required'),
			array('create_time, update_time, author_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>128),
			array('atcontent_publication_id', 'length', 'max'=>256),
			array('content, atcontent_embed_code', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, create_time, update_time, atcontent_embed_code, atcontent_publication_id, author_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'content' => 'Content',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'atcontent_embed_code' => 'AtContent embed code',
			'atcontent_publication_id' => 'AtContent publication ID',
			'author_id' => 'Author',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('atcontent_embed_code',$this->atcontent_embed_code,true);
		$criteria->compare('atcontent_publication_id',$this->atcontent_publication_id,true);
		$criteria->compare('author_id',$this->author_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    protected function beforeSave()
    {

        $this->isNewRecord ? $this->create_time=$this->update_time=time() : $this->update_time=time();

        // Create or update AtContent publication
        $this->isNewRecord ? $url = 'http://api.atcontent.com/v1/publication.new' : $url = 'http://api.atcontent.com/v1/publication.update';

        // Split content to face and body with <hr /> tag
        $parts = preg_split('/<hr ?\/?>/', $this->content);
        $face = $parts[0];
        $body = isset($parts[1]) ? $parts[1] : '';

        $options = array(
            'Key' => Yii::app()->params['atcontentAPIKey'],
            'Title' => $this->title,
            'FreeFace' => $face,
            'FreeContent' => $body,
        );

        // Need to update AtContent publication
        if (!$this->isNewRecord)
            $options['PublicationID'] = $this->atcontent_publication_id;

        $responseJSON = Yii::app()->CURL->run($url, FALSE, $options);

        $response = json_decode($responseJSON);

        // We create AtContent publication
        if (isset($response->Code) && $this->isNewRecord) {
          $this->atcontent_embed_code = $response->Code;
          $this->atcontent_publication_id = $response->PublicationID;
        }

        return parent::beforeSave();
    }
}