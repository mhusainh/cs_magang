<?php

namespace App\Repositories;

use App\Models\PesertaPpdb as Peserta;
use Illuminate\Database\Eloquent\Collection;


class PesertaRepository
{
    public function __construct(private Peserta $model) {}

    public function create(array $data): Peserta
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?Peserta
    {
        return $this->model->withTrashed()->with([
            'jurusan1' => function ($query) {
                $query->withTrashed();
            },
            'biodataOrtu' => function ($query) {
                $query->withTrashed()->with([
                    'pekerjaanAyah' => function ($q) {
                        $q->withTrashed();
                    },
                    'pekerjaanIbu' => function ($q) {
                        $q->withTrashed();
                    },
                    'penghasilanOrtu' => function ($q) {
                        $q->withTrashed();
                    }
                ]);
            }
        ])->where('id', $id)->first();
    }

    public function findByUserId(int $userId): ?Peserta
    {
        return $this->model->with([
            'jurusan1' => function ($query) {
                $query->withTrashed();
            },
            'biodataOrtu' => function ($query) {
                $query->withTrashed()->with([
                    'pekerjaanAyah' => function ($q) {
                        $q->withTrashed();
                    },
                    'pekerjaanIbu' => function ($q) {
                        $q->withTrashed();
                    },
                    'penghasilanOrtu' => function ($q) {
                        $q->withTrashed();
                    }
                ]);
            },
            'berkas'
        ])->where('user_id', $userId)->first();
    }

    public function update(Peserta $peserta, array $data): bool
    {
        return $peserta->update($data);
    }

    public function delete(Peserta $peserta): bool
    {
        return $peserta->delete();
    }

    public function getAll(array $filters = [])
    {
        $query = $this->model->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%")
                    ->orWhereHas('jurusan1', function ($q) use ($search) {
                        $q->withTrashed()->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('biodataOrtu', function ($q) use ($search) {
                        $q->withTrashed()->where('nama_ayah', 'like', "%{$search}%")
                            ->orWhere('nama_ibu', 'like', "%{$search}%")
                            ->orWhereHas('pekerjaanAyah', function ($q) use ($search) {
                                $q->withTrashed()->where('nama', 'like', "%{$search}%");
                            })
                            ->orWhereHas('pekerjaanIbu', function ($q) use ($search) {
                                $q->withTrashed()->where('nama', 'like', "%{$search}%");
                            })
                            ->orWhereHas('penghasilanOrtu', function ($q) use ($search) {
                                $q->withTrashed()->where('penghasilan_ortu', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filter by date range
        if (isset($filters['start_date']) && $filters['start_date'] !== '') {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date']) && $filters['end_date'] !== '') {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        // Filter by jenjang_sekolah
        if (isset($filters['jenjang_sekolah']) && $filters['jenjang_sekolah'] !== '') {
            $query->where('jenjang_sekolah', $filters['jenjang_sekolah']);
        }

        // Filter by angkatan
        if (isset($filters['angkatan']) && $filters['angkatan']!== '') {
            $query->where('angkatan', $filters['angkatan']);
        }

        // Sorting functionality
        if (isset($filters['sort_by']) && $filters['sort_by'] !== '') {
            $sortField = $filters['sort_by'];
            $sortDirection = isset($filters['sort_direction']) && strtolower($filters['sort_direction']) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($sortField, $sortDirection);
        } else {
            // Default sorting by created_at in descending order
            $query->orderBy('created_at', 'desc');
        }

        $paginator = $query->with([
            'jurusan1' => function ($query) {
                $query->withTrashed();
            },
            'biodataOrtu' => function ($query) {
                $query->withTrashed()->with([
                    'pekerjaanAyah' => function ($q) {
                        $q->withTrashed();
                    },
                    'pekerjaanIbu' => function ($q) {
                        $q->withTrashed();
                    },
                    'penghasilanOrtu' => function ($q) {
                        $q->withTrashed();
                    }
                ]);
            }
        ])->paginate($filters['per_page'] ?? 10);
        return $paginator->appends(request()->query());
    }

    public function getTrash(array $filters = [])
    {
        $query = $this->model->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%")
                    ->orWhereHas('jurusan1', function ($q) use ($search) {
                        $q->withTrashed()->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('biodataOrtu', function ($q) use ($search) {
                        $q->withTrashed()->where('nama_ayah', 'like', "%{$search}%")
                            ->orWhere('nama_ibu', 'like', "%{$search}%")
                            ->orWhereHas('pekerjaanAyah', function ($q) use ($search) {
                                $q->withTrashed()->where('nama', 'like', "%{$search}%");
                            })
                            ->orWhereHas('pekerjaanIbu', function ($q) use ($search) {
                                $q->withTrashed()->where('nama', 'like', "%{$search}%");
                            })
                            ->orWhereHas('penghasilanOrtu', function ($q) use ($search) {
                                $q->withTrashed()->where('penghasilan_ortu', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filter by date range
        if (isset($filters['start_date']) && $filters['start_date'] !== '') {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date']) && $filters['end_date'] !== '') {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        // Filter by jenjang_sekolah
        if (isset($filters['jenjang_sekolah']) && $filters['jenjang_sekolah'] !== '') {
            $query->where('jenjang_sekolah', $filters['jenjang_sekolah']);
        }

        // Filter by angkatan
        if (isset($filters['angkatan']) && $filters['angkatan']!== '') {
            $query->where('angkatan', $filters['angkatan']);
        }

        // Sorting functionality
        if (isset($filters['sort_by']) && $filters['sort_by'] !== '') {
            $sortField = $filters['sort_by'];
            $sortDirection = isset($filters['sort_direction']) && strtolower($filters['sort_direction']) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($sortField, $sortDirection);
        } else {
            // Default sorting by created_at in descending order
            $query->orderBy('created_at', 'desc');
        }

        $paginator = $query->with([
            'jurusan1' => function ($query) {
                $query->withTrashed();
            },
            'biodataOrtu' => function ($query) {
                $query->withTrashed()->with([
                    'pekerjaanAyah' => function ($q) {
                        $q->withTrashed();
                    },
                    'pekerjaanIbu' => function ($q) {
                        $q->withTrashed();
                    },
                    'penghasilanOrtu' => function ($q) {
                        $q->withTrashed();
                    }
                ]);
            }
        ])->onlyTrashed()->paginate($filters['per_page'] ?? 10);
        return $paginator->appends(request()->query());
    }
    
    public function GetPeringkat(int $jurusan1_id, $jenjang_sekolah, $angkatan): Collection
    {
        // Dapatkan jurusan dan jenjang sekolah dari user saat ini

        $query = $this->model
            ->leftJoin('tagihans', function ($join) {
                $join->on('peserta_ppdbs.user_id', '=', 'tagihans.user_id')
                    ->where('tagihans.nama_tagihan', 'book_vee')
                    ->where('tagihans.status', 1 ); // filter langsung saat join
                })
                ->where('peserta_ppdbs.angkatan', $angkatan)
                ->whereNotNull('peserta_ppdbs.wakaf');
            
        // Filter berdasarkan jurusan dan jenjang sekolah
        if ($jurusan1_id !== null) {
            $query->where('peserta_ppdbs.jurusan1_id', $jurusan1_id)
                ->where('peserta_ppdbs.jenjang_sekolah', $jenjang_sekolah);
        }

        $selectColumns = [
            'tagihans.*',
            'peserta_ppdbs.id as peserta_id',
            'peserta_ppdbs.nama as peserta_nama',
            'peserta_ppdbs.wakaf as wakaf',
            'peserta_ppdbs.book_vee as book_vee'
        ];

        return $query->select($selectColumns)
            // Urutkan peserta dengan book_vee di tagihan terlebih dahulu (tidak null)
            ->orderByRaw('CASE WHEN tagihans.id IS NOT NULL THEN 1 ELSE 0 END DESC')
            // Kemudian urutkan berdasarkan book_vee, wakaf, dan created_at
            ->orderBy('wakaf', 'desc')
            ->orderBy('tagihans.created_at', 'asc')
            ->get();
    }

    public function restore(Peserta $peserta): bool
    {
        return $peserta->restore();
    }
}
