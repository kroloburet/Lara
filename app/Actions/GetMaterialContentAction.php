<?php

namespace App\Actions;

use App\Contracts\ActionContract;

class GetMaterialContentAction implements ActionContract
{
    /**
     * Handle get content of material
     *
     * @param array $data
     * @return array
     */
    public function handle(array $data): array
    {
        $responseData = ['fields' => null];
        $isStatic = config("app.materials.types.{$data['type']}.static");
        $material = materialBuilder($data['type']);
        $material = $isStatic ? $material->first() : $material->firstWhere('alias', $data['alias']);

        if(empty($material)) {
            return $responseData;
        }

        $content = $material->contents()
            ->firstWhere('locale', $data['locale']);
        if (! empty($content)) {
            $responseData['fields'] = $content;
        }

        return $responseData;
    }
}
