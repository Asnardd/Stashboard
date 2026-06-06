<?php

namespace App\Console\Commands;

use App\Models\ItemType;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeTypeResource extends Command
{
    protected $signature = 'stashboard:make-type-resource {name : The item type name (e.g. RAM, Cable)}';
    protected $description = 'Generate a Filament resource for an item type';

    public function handle(): void
    {
        $name         = Str::studly($this->argument('name'));
        $plural       = Str::plural($name);
        $ns           = "App\\Filament\\Resources\\ItemTypes\\{$name}";
        $resourceFQCN = "{$ns}\\{$name}Resource";
        $dir    = app_path("Filament/Resources/ItemTypes/{$name}");
        $pages  = "{$dir}/Pages";

        if (!ItemType::where('name', $name)->exists()) {
            $this->warn("No ItemType named '{$name}' found in the database.");
            $this->line("Create it in the panel or run the seeder, then set it to active.");
        }

        if (is_dir($dir)) {
            $this->error("Resource for '{$name}' already exists at {$dir}");
            return;
        }

        if (!mkdir($pages, 0755, true) && !is_dir($pages)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $pages));
        }

        file_put_contents("{$dir}/{$name}Resource.php", $this->resourceStub($name, $plural, $ns));
        file_put_contents("{$pages}/List{$plural}.php",  $this->listStub($name, $plural, $ns));
        file_put_contents("{$pages}/Create{$name}.php",  $this->createStub($name, $plural, $ns));
        file_put_contents("{$pages}/Edit{$name}.php",    $this->editStub($name, $plural, $ns));

        $this->info("Resource created for '{$name}':");
        $this->line("  {$dir}/{$name}Resource.php");
        $this->line("  {$pages}/List{$plural}.php");
        $this->line("  {$pages}/Create{$name}.php");
        $this->line("  {$pages}/Edit{$name}.php");
    }

    private function resourceStub(string $name, string $plural, string $ns): string
    {
        return <<<PHP
        <?php

        namespace {$ns};

        use App\Filament\Resources\ItemTypes\BaseItemTypeResource;
        use {$ns}\Pages\Create{$name};
        use {$ns}\Pages\Edit{$name};
        use {$ns}\Pages\List{$plural};

        class {$name}Resource extends BaseItemTypeResource
        {
            protected static string \$typeName = '{$name}';

            public static function getPages(): array
            {
                return [
                    'index'  => List{$plural}::route('/'),
                    'create' => Create{$name}::route('/create'),
                    'edit'   => Edit{$name}::route('/{record}/edit'),
                ];
            }
        }
        PHP;
    }

    private function listStub(string $name, string $plural, string $ns): string
    {
        $resourceFQCN = "{$ns}\\{$name}Resource";
        return <<<PHP
        <?php

        namespace {$ns}\Pages;

        use {$resourceFQCN};
        use Filament\Actions\CreateAction;
        use Filament\Resources\Pages\ListRecords;

        class List{$plural} extends ListRecords
        {
            protected static string \$resource = {$name}Resource::class;

            protected function getHeaderActions(): array
            {
                return [
                    CreateAction::make(),
                ];
            }
        }
        PHP;
    }

    private function createStub(string $name, string $plural, string $ns): string
    {
        $resourceFQCN = "{$ns}\\{$name}Resource";
        return <<<PHP
        <?php

        namespace {$ns}\Pages;

        use {$resourceFQCN};
        use Filament\Resources\Pages\CreateRecord;

        class Create{$name} extends CreateRecord
        {
            protected static string \$resource = {$name}Resource::class;
        }
        PHP;
    }

    private function editStub(string $name, string $plural, string $ns): string
    {
        $resourceFQCN = "{$ns}\\{$name}Resource";
        return <<<PHP
        <?php

        namespace {$ns}\Pages;

        use {$resourceFQCN};
        use Filament\Actions\DeleteAction;
        use Filament\Resources\Pages\EditRecord;

        class Edit{$name} extends EditRecord
        {
            protected static string \$resource = {$name}Resource::class;

            protected function getHeaderActions(): array
            {
                return [
                    DeleteAction::make(),
                ];
            }
        }
        PHP;
    }
}
