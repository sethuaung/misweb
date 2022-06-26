<?php
namespace App\Src\Backpack;

class CrudPanel extends \Backpack\CRUD\CrudPanel
{
    /**
    * The method we want to override
    */
    public function getEntry($id)
    {
        if (!$this->entry) {
            $this->entry = $this->model->findOrFail($id);
            $this->entry = $this->entry->withFakes();
        }
        return $this->entry;
    }
}
?>
