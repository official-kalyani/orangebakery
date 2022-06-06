<?php
namespace App\Exports;
use App\Models\Testimonial;
use Maatwebsite\Excel\Concerns\FromCollection;
class TestimonialExport implements FromCollection
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection()
    {
        return Testimonial::all();
    }
}