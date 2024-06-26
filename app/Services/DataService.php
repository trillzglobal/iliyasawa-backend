<?php
namespace App\Services;
use Illuminate\Support\Facades\Schema;

class DataService
{
    /**
     * @throws Exception
     */
    public function createData(string $modelName, array $data)
    {
        // Check if the model class exists
        $modelClass = $this->getModelClass($modelName);

        if (!$modelClass) {
            throw new \Exception("Model '{$modelName}' not found.");
        }

        // Check if the table exists for the given model
        $tableName = (new $modelClass)->getTable();
        if (!Schema::hasTable($tableName)) {
            throw new \Exception("Table '{$tableName}' not found for model '{$modelName}'.");
        }

        // Create a new instance of the model
        $model = new $modelClass();

        // Fill the model with the provided data
        $model->fill($data);

        // Save the model
        $model->save();
        return $model;
    }

    /**
     * @throws Exception
     */
    public function getModelData(string $modelName)
    {
        $modelClass = $this->getModelClass($modelName);

        if (!$modelClass) {
            throw new \Exception("Model '{$modelName}' not found.");
        }

        $model = $modelClass::get();

        if (!$model) {
            throw new \Exception("Model '{$modelName}' not found.");
        }

        return $model;
    }

    /**
     * @throws Exception
     */
    public function getModelDataRelationship(string $modelName, bool $includeRoles = false)
    {
        $modelClass = $this->getModelClass($modelName);

        if (!$modelClass) {
            throw new \Exception("Model '{$modelName}' not found.");
        }

        if ($includeRoles && $modelName === 'User') {
            $query = $modelClass::withRoles();
        } else {
            $query = $modelClass::query();
        }

        $model = $query->get();

        if ($model->isEmpty()) {
            throw new \Exception("No data found for model '{$modelName}'.");
        }

        if ($includeRoles && $modelName === 'User') {
            $model->each(function ($user) {
                $user->role_given = $user->roles_string ? explode(',', $user->roles_string) : [];
            });
        }

        return $model;
    }

//    public function getModelDataRelationship(string $modelName, bool $includeRoles = false)
//    {
//        $modelClass = $this->getModelClass($modelName);
//
//        if (!$modelClass) {
//            throw new \Exception("Model '{$modelName}' not found.");
//        }
//
//        $query = $modelClass::query();
//
//        if ($includeRoles && $modelName === 'User') {
//            $query->withRoles();
//        }
//
//        $model = $query->get();
//
//        if ($model->isEmpty()) {
//            throw new \Exception("No data found for model '{$modelName}'.");
//        }
//
//        return $model;
//    }
//    public function getModelDataRelationship(string $modelName, string $relationship = null)
//    {
//        $modelClass = $this->getModelClass($modelName);
//
//        if (!$modelClass) {
//            throw new \Exception("Model '{$modelName}' not found.");
//        }
//
//        $query = $modelClass::query();
//
//        if ($relationship) {
//            $query->with($relationship);
//        }
//
//        $model = $query->get();
//
//        if ($model->isEmpty()) {
//            throw new \Exception("No data found for model '{$modelName}'.");
//        }
//
//        return $model;
//    }
//    public function getModelDataRelationship(string $modelName, string $relationship = null)
//    {
//        $modelClass = $this->getModelClass($modelName);
//
//        if (!$modelClass) {
//            throw new \Exception("Model '{$modelName}' not found.");
//        }
//
//        $query = $modelClass::query();
//
//        if ($relationship && method_exists($modelClass, $relationship)) {
//            $query->with($relationship);
//        }
//
//        $model = $query->get();
//
//        if ($model->isEmpty()) {
//            throw new \Exception("No data found for model '{$modelName}'.");
//        }
//
//        return $model;
//    }

    /**
     * @throws Exception
     */
    public function getModelById(string $modelName, int $id)
    {
        $modelClass = $this->getModelClass($modelName);

        if (!$modelClass) {
            throw new \Exception("Model '{$modelName}' not found.");
        }

        $model = $modelClass::find($id);

        if (!$model) {
            throw new \Exception("Model '{$modelName}' with ID {$id} not found.");
        }

        return $model;
    }

    /**
     * @throws Exception
     */
    public function getModelDataWhereNotNull(string $modelName, $columnName)
    {
        $modelClass = $this->getModelClass($modelName);

        if (!$modelClass) {
            throw new \Exception("Model '{$modelName}' not found.");
        }

        $model = $modelClass::whereNotNull($columnName)->get();;

        if (!$model) {
            throw new \Exception("Model '{$modelName}' not found.");
        }

        return $model;
    }

    /**
     * @throws Exception
     */
    public function updateModel(string $modelName, int $id, array $data)
    {
        $model = $this->getModelById($modelName, $id);

        $model->fill($data);
        $model->save();

        return $model;
    }

    /**
     * @throws Exception
     */
    public function deleteModel(string $modelName, int $id): bool
    {
        $model = $this->getModelById($modelName, $id);

        $model->delete();

        return true;
    }

    private function getModelClass(string $modelName): ?string
    {
        $modelClass = 'App\\Models\\' . $modelName;
        if (!class_exists($modelClass)) {
            return null;
        }

        return $modelClass;
    }
}
