<?php

namespace App\Repositories;

use App\Models\UKM;

class UKMRepository
{
    public function all()
    {
        return UKM::all();
    }

    public function find($id)
    {
        return UKM::findOrFail($id);
    }

    public function create(array $data)
    {
        return UKM::create($data);
    }

    public function update($id, array $data)
    {
        $ukm = UKM::findOrFail($id);
        $ukm->update($data);

        return $ukm;
    }

    public function delete($id)
    {
        $ukm = UKM::findOrFail($id);
        return $ukm->delete();
    }

    public function withStats()
    {
        return UKM::withCount(['memberships', 'events'])->get();
    }
}