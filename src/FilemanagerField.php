<?php

namespace Clevyr\Filemanager;

use Illuminate\Validation\Rule;
use Clevyr\Filemanager\Http\Services\FileManagerService;
use Clevyr\Filemanager\Traits\CoverHelpers;
use Laravel\Nova\Contracts\Cover;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\SupportsDependentFields;

class FilemanagerField extends Field implements Cover
{
    use CoverHelpers,
        SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'filemanager-field';

    /**
     * The validation rules for upload files.
     *
     * @var array
     */
    public $uploadRules = [];

    /**
     * @var bool
     */
    protected $createFolderButton;

    /**
     * @var bool
     */
    protected $uploadButton;

    /**
     * @var bool
     */
    protected $dragAndDropUpload;

    /**
     * @var bool
     */
    protected $renameFolderButton;

    /**
     * @var bool
     */
    protected $deleteFolderButton;

    /**
     * @var bool
     */
    protected $renameFileButton;

    /**
     * @var bool
     */
    protected $deleteFileButton;

    /**
     * @var bool
     */
    protected $downloadFileButton;

    /**
     * The callback used to determine if the field is readonly.
     *
     * @var Closure
     */
    public $readonlyCallback;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->setButtons();

        $this->withMeta(['visibility' => 'public']);
        $this->rounded();
    }

    /**
     * Set display in details and list as image or icon.
     *
     * @return $this
     */
    public function displayAsImage()
    {
        return $this->withMeta(['display' => 'image']);
    }

    public function detailDisplayAsImage()
    {
        return $this->withMeta(['displayDetail' => 'image']);
    }

    public function indexDisplayAsImage()
    {
        return $this->withMeta(['displayIndex' => 'image']);
    }

    public function displayNormal()
    {
        return $this->withMeta(['display' => 'normal']);
    }

    public function detailDisplayNormal()
    {
        return $this->withMeta(['displayDetail' => 'normal']);
    }

    public function indexDisplayNormal()
    {
        return $this->withMeta(['displayIndex' => 'normal']);
    }

    /**
     * Set current folder for the field.
     *
     * @param   string  $folderName
     *
     * @return  $this
     */
    public function folder($folderName)
    {
        $folder = is_callable($folderName) ? call_user_func($folderName) : $folderName;

        return $this->withMeta(['folder' => $folder, 'home' => $folder]);
    }

    /**
     * Set current folder for the field.
     *
     * @param   string | function  $rules
     *
     * @return  $this
     */
    public function validateUpload($rules)
    {
        $this->uploadRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * Set filter for the field.
     *
     * @param   string  $folderName
     *
     * @return  $this
     */
    public function filterBy($filter)
    {
        $defaultFilters = config('filemanager.filters', []);

        if (count($defaultFilters) > 0) {
            $filters = array_change_key_case($defaultFilters);

            if (isset($filters[$filter])) {
                $filteredExtensions = $filters[$filter];

                return $this->withMeta(['filterBy' => $filter]);
            }
        }

        return $this;
    }

    /**
     * Set display in details and list as image or icon.
     *
     * @return $this
     */
    public function privateFiles()
    {
        return $this->withMeta(['visibility' => 'private']);
    }

    /**
     * Hide Create button Folder.
     *
     * @return $this
     */
    public function hideCreateFolderButton()
    {
        $this->createFolderButton = false;

        return $this;
    }

    /**
     * Hide Upload button.
     *
     * @return $this
     */
    public function hideUploadButton()
    {
        $this->uploadButton = false;

        return $this;
    }

    /**
     * Hide Rename folder button.
     *
     * @return $this
     */
    public function hideRenameFolderButton()
    {
        $this->renameFolderButton = false;

        return $this;
    }

    /**
     * Hide Delete folder button.
     *
     * @return $this
     */
    public function hideDeleteFolderButton()
    {
        $this->deleteFolderButton = false;

        return $this;
    }

    /**
     * Hide Rename file button.
     *
     * @return $this
     */
    public function hideRenameFileButton()
    {
        $this->renameFileButton = false;

        return $this;
    }

    /**
     * Hide Rename file button.
     *
     * @return $this
     */
    public function hideDeleteFileButton()
    {
        $this->deleteFileButton = false;

        return $this;
    }

    /**
     * Hide Rename file button.
     *
     * @return $this
     */
    public function hideDownloadFileButton()
    {
        $this->downloadFileButton = false;

        return $this;
    }

    /**
     * No drag and drop file upload.
     *
     * @return $this
     */
    public function noDragAndDropUpload()
    {
        $this->dragAndDropUpload = false;

        return $this;
    }

    /**
     * Set the callback used to determine if the field is readonly.
     *
     * @param  Closure|bool  $callback
     * @return $this
     */
    public function readonly($callback = true)
    {
        $this->readonlyCallback = $callback;

        return $this;
    }

    /**
     * Determine if the field is readonly.
     *
     * @param NovaRequest $request
     * @return bool
     */
    public function isReadonly(NovaRequest $request): bool
    {
        return with($this->readonlyCallback, function ($callback) use ($request) {
            if ($callback === true || (is_callable($callback) && call_user_func($callback, $request))) {
                $this->setReadonlyAttribute();

                return true;
            }

            return false;
        });
    }

    /**
     * Set the field to a readonly field.
     *
     * @return $this
     */
    protected function setReadonlyAttribute()
    {
        $this->withMeta(['extraAttributes' => ['readonly' => true]]);

        return $this;
    }

    /**
     * Resolve the thumbnail URL for the field.
     *
     * @return string|null
     */
    public function resolveInfo()
    {
        if ($this->value) {
            $service = new FileManagerService();

            $data = $service->getFileInfoAsArray($this->value);

            if (empty($data)) {
                return [];
            }

            return $this->fixNameLabel($data);
        }

        return [];
    }

    /**
     * Resolve the thumbnail URL for the field.
     *
     * @return string|null
     */
    public function resolveThumbnailUrl()
    {
        if ($this->value) {
            $service = new FileManagerService();

            $data = $service->getFileInfoAsArray($this->value);

            if ((isset($data['type']) && $data['type'] !== 'image') || empty($data)) {
                return;
            }

            return $data['url'];
        }
    }

    /**
     * Get additional meta information to merge with the element payload.
     *
     * @return array
     */
    public function meta()
    {
        return array_merge(
            $this->resolveInfo(),
            $this->buttons(),
            $this->getUploadRules(),
            $this->getCoverType(),
            $this->meta
        );
    }

    /**
     * Set default button options.
     */
    private function setButtons()
    {
        $this->createFolderButton = config('filemanager.buttons.create_folder', true);
        $this->uploadButton = config('filemanager.buttons.upload_button', true);
        $this->dragAndDropUpload = config('filemanager.buttons.upload_drag', true);
        $this->renameFolderButton = config('filemanager.buttons.rename_folder', true);
        $this->deleteFolderButton = config('filemanager.buttons.delete_folder', true);
        $this->renameFileButton = config('filemanager.buttons.rename_file', true);
        $this->deleteFileButton = config('filemanager.buttons.delete_file', true);
        $this->downloadFileButton = config('filemanager.buttons.download_file', true);
    }

    /**
     * Return correct buttons.
     *
     * @return array
     */
    private function buttons()
    {
        $buttons = [
            'create_folder' => $this->createFolderButton,
            'upload_button' => $this->uploadButton,
            'upload_drag' => $this->dragAndDropUpload,
            'rename_folder' => $this->renameFolderButton,
            'delete_folder' => $this->deleteFolderButton,
            'rename_file' => $this->renameFileButton,
            'delete_file' => $this->deleteFileButton,
            'download_file' => $this->downloadFileButton,
        ];

        return ['buttons' => $buttons];
    }

    /**
     * Return upload rules.
     *
     * @return  array
     */
    private function getUploadRules()
    {
        return ['upload_rules' => $this->uploadRules];
    }

    /**
     * Return cover type.
     *
     * @return  array
     */
    private function getCoverType()
    {
        return ['rounded' => $this->isRounded()];
    }

    /**
     * FIx name label.
     *
     * @param array $data
     *
     * @return array
     */
    private function fixNameLabel(array $data): array
    {
        $data['filename'] = $data['name'];
        unset($data['name']);

        return $data;
    }
}
