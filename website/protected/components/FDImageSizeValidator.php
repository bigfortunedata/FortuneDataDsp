<?php

/**
 * FDImageSizeValidator validates image with width and height
 * The following sizes are allowed:
 * 300×250, 728×90, 160×600, 468×60, 120×600, 300×50 (mobile), and 320×50
 *
 * @author shihaow
 * @version 1.0
 */
class FDImageSizeValidator extends CFileValidator {
    /**
     * @var string image dimension error message
     */
    public $dimensionError;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute) {
        $file = $object->$attribute;

        if (!$file instanceof CUploadedFile) {
            $file = CUploadedFile::getInstance($object, $attribute);

            if (null === $file)
                return;
        }

        $data = file_exists($file->tempName) ? getimagesize($file->tempName) : false;
        if ($data !== false) {
	        // Validate width/height
            if (!(($data[0] == 300 && $data[1] == 250) |
				($data[0] == 728 && $data[1] == 90) ||
				($data[0] == 160 && $data[1] == 600) ||
				($data[0] == 468 && $data[1] == 60) ||
				($data[0] == 120 && $data[1] == 600) ||
				($data[0] == 300 && $data[1] == 50) ||
				($data[0] == 320 && $data[1] == 50))) {
                $message = $this->dimensionError ? $this->dimensionError : Yii::t('yii', 'Image dimension is not supported.');
                $this->addError($object, $attribute, $message, null);
                return;
            }
        }
    }
}

?>