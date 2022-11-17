<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Documents;
use App\Models\Paragraphs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParagraphSeeder extends Seeder
{
    static function addParagraphs($document_url, $extractor) {
        $docs_data = json_decode(file_get_contents($url), true)['data'];

        foreach($docs_data as $doc){
            $ret=Documents::updateOrCreate(
                ['case_number' => $doc['case_number']],
                [
                    'case_number' => $doc['case_number'],
                    'title' => $doc['title'],
                    'date' => Carbon::createFromFormat('d/m/Y', $doc['date'])->format('Y-m-d'),
                    'document_link' => $doc['document_href']
                ]
            );

            $paragraphs_chunk=[];
            $paragraphs=$doc['paragraphs'][$extractor];
            foreach($paragraphs as $para){
                $id=(!is_null($ret['id'])) ? $ret['id'] : $ret['doc_id'];

                $paragraphs_chunk[]= [
                    'paragraph_num' => $para['paragraph_number'],
                    'doc_id' => $id,
                    'content' => $para['content'],
                    'reference' => $para['reference']
                    'page' => $para['page'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            foreach(array_chunk($paragraphs_chunk,1000) as $chunk){
                Paragraphs::upsert($chunk,['paragraph_num','doc_id'],['content','page','reference']);
            }
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url = env('DOCUMENTS_DATA_LINK');
        $extractor = env('DOCUMENTS_EXTRACTOR') ?: 'adobe_api';
        $docs_data = json_decode(file_get_contents($url), true);

        foreach($docs_data as $doc){
            ParagraphsSeeder::addParagraphs($doc['download_url'], $extractor);
        }
    }
}
