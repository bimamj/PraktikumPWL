<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //section1 post details
                Section::make("Post Details")
                ->description("Fill in the details of the post")
                ->icon("heroicon-o-document-text")
                ->schema([
                // TextInput::make("title")->required()->minLength(5),
                TextInput::make("title")
                ->rules('required | min:5 | max:10')
                ->validationMessages([
                    'required' => 'The title field is required.',
                    'min' => 'The title must be at least 5 characters.',
                    'max' => 'The title must not exceed 10 characters.',
                ]),

                TextInput::make("slug")
                ->rules('required | min:3')
                ->unique()
                ->validationMessages([
                    'unique' => 'The slug must be unique. The provided slug already exists.',
                    'min' => 'The slug must be at least 3 characters.',
                ]),

                Select::make("category_id")
                    ->relationship("category", "name")
                    ->options(Category::all()->pluck("name", "id"))
                    ->rules('required')
                    // ->preload()
                    ->searchable(),
                ColorPicker::make("color"),

                MarkdownEditor::make("content")->columnSpanFull(),
                // RichEditor::make("content"),
                ])->columns(2)->columnSpan(2),

                // grouping fieds to 2 columns
                Group::make([
                //section 2 image
                Section::make("Image Upload")
                ->description("Upload an image for the post")
                ->icon("heroicon-o-photo")
                ->schema([
                    FileUpload::make("image")
                        ->required()
                        ->disk("public")
                        ->directory("posts"),
                ]),
                //section 3 meta
                Section::make("Meta Information")
                ->description("Manage the meta information for the post")
                ->icon("heroicon-o-cog")
                ->schema([
                TagsInput::make("tags"),
                Checkbox::make("published"),
                ])->columns(2),
            
                DateTimePicker::make("published_at"),
                ])->columnSpan(1)

            ])->columns(3);
    }
}
