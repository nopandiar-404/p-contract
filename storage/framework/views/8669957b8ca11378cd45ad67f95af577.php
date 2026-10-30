<!-- resources/views/clients/index.blade.php -->


<?php $__env->startSection('title', 'Daftar Vendor'); ?>
<?php $__env->startSection('subtitle', 'Manajemen data vendor'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .vendor-header-wrapper {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 1.25rem;
        }

        .vendor-header-title {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .vendor-header-controls {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.625rem;
            flex-wrap: wrap;
            width: min(100%, 620px);
        }

        .vendor-search {
            flex: 1 1 220px;
            min-width: 180px;
        }

        .vendor-search .input-group-text,
        .vendor-search .form-control {
            min-height: 42px;
            border-color: rgba(15, 35, 77, 0.16);
            background: #fff;
        }

        .vendor-search .input-group-text {
            padding-inline: 0.875rem;
            border-radius: 0.625rem 0 0 0.625rem;
        }

        .vendor-search .form-control {
            border-left: 0;
            border-radius: 0 0.625rem 0.625rem 0;
            box-shadow: none;
        }

        .vendor-search .form-control:focus {
            border-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        }

        .vendor-status-button,
        .vendor-create-button {
            min-height: 42px;
            border-radius: 0.625rem;
            padding: 0.55rem 0.9rem;
            font-weight: 600;
            white-space: nowrap;
            transition: background-color 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
        }

        .vendor-status-button {
            border: 1px solid rgba(255, 255, 255, 0.62);
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
        }

        .vendor-status-button:hover,
        .vendor-status-button:focus-visible {
            color: #fff;
            background: rgba(255, 255, 255, 0.22);
            border-color: #fff;
        }

        .vendor-create-button {
            color: #0f234d;
            background: #fff;
            border: 1px solid #fff;
        }

        .vendor-create-button:hover,
        .vendor-create-button:focus-visible {
            color: #0f234d;
            background: #eaf2ff;
            border-color: #eaf2ff;
            transform: translateY(-1px);
        }

        @media (max-width: 992px) {
            .vendor-header-wrapper {
                flex-direction: column;
                align-items: stretch;
            }

            .vendor-header-controls {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media (max-width: 576px) {
            .vendor-header-title h5 {
                font-size: 1.1rem;
            }

            .vendor-header-controls {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.5rem;
            }

            .vendor-search {
                grid-column: 1 / -1;
                width: 100%;
            }

            .vendor-header-controls .dropdown {
                width: 100%;
            }

            .vendor-status-button,
            .vendor-create-button {
                width: 100%;
                justify-content: center;
                padding-inline: 0.5rem;
            }
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white py-3 px-3">
                    <div class="vendor-header-wrapper">
                        <div class="vendor-header-title">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2"
                                style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">List Vendor</h5>
                                <small class="opacity-75">Manajemen data vendor</small>
                            </div>
                        </div>
                        
                        <div class="vendor-header-controls">
                            <!-- Search Input -->
                            <div class="input-group vendor-search">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-start-0"
                                    placeholder="Cari vendor..." aria-label="Cari vendor" value="<?php echo e(request('search', '')); ?>">
                            </div>

                            <!-- Filter Status -->
                            <div class="dropdown flex-shrink-0">
                                <button class="btn vendor-status-button dropdown-toggle d-flex align-items-center"
                                    type="button" id="statusFilter"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-2"></i>
                                    <span>
                                        <?php if(request('status') == 'active'): ?>
                                            Aktif
                                        <?php elseif(request('status') == 'inactive'): ?>
                                            Non-Aktif
                                        <?php else: ?>
                                            Status
                                        <?php endif; ?>
                                    </span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="statusFilter">
                                    <li><a class="dropdown-item <?php echo e(!request('status') ? 'active' : ''); ?>"
                                            href="<?php echo e(request()->fullUrlWithQuery(['status' => ''])); ?>">
                                            <i class="fas fa-list me-2"></i> Semua
                                        </a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item <?php echo e(request('status') == 'active' ? 'active' : ''); ?>"
                                            href="<?php echo e(request()->fullUrlWithQuery(['status' => 'active'])); ?>">
                                            <i class="fas fa-check-circle text-success me-2"></i> Aktif
                                        </a></li>
                                    <li><a class="dropdown-item <?php echo e(request('status') == 'inactive' ? 'active' : ''); ?>"
                                            href="<?php echo e(request()->fullUrlWithQuery(['status' => 'inactive'])); ?>">
                                            <i class="fas fa-times-circle text-danger me-2"></i> Non-Aktif
                                        </a></li>
                                </ul>
                            </div>

                            <!-- Add Button -->
                            <a href="<?php echo e(route('clients.create')); ?>" class="btn vendor-create-button d-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="fas fa-plus-circle me-2"></i> Vendor Baru
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filter Chips (Responsive) -->
                <?php
                    function getStatusBadgeClass($status)
                    {
                        $classes = [
                            'active' => 'bg-success',
                            'inactive' => 'bg-danger',
                        ];
                        return $classes[$status] ?? 'bg-secondary';
                    }
                ?>

                <?php if(request()->has('status') || request()->has('search') || request()->has('sort')): ?>
                    <div class="card-body py-2 border-bottom bg-light">
                        <div class="d-flex align-items-center flex-column flex-md-row gap-2">
                            <small class="text-muted me-md-2 d-none d-md-block">
                                <i class="fas fa-sliders-h me-1"></i> Filter:
                            </small>

                            <div class="d-flex align-items-center flex-wrap gap-2 w-100">
                                <?php if(request()->has('status') && request('status') != ''): ?>
                                    <span class="badge <?php echo e(getStatusBadgeClass(request('status'))); ?> d-flex align-items-center gap-1 flex-shrink-0">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="d-none d-md-inline">Status: <?php echo e(request('status') == 'active' ? 'Aktif' : 'Non-Aktif'); ?></span>
                                        <span class="d-md-none"><?php echo e(request('status') == 'active' ? 'Aktif' : 'N.Aktif'); ?></span>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['status' => ''])); ?>"
                                            class="text-white ms-1 text-decoration-none" style="cursor: pointer;">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if(request()->has('search') && request('search') != ''): ?>
                                    <span class="badge bg-primary d-flex align-items-center gap-1 flex-shrink-0">
                                        <i class="fas fa-search"></i>
                                        <span class="d-none d-md-inline"><?php echo e(substr(request('search'), 0, 15)); ?><?php echo e(strlen(request('search')) > 15 ? '...' : ''); ?></span>
                                        <span class="d-md-none"><?php echo e(substr(request('search'), 0, 10)); ?>...</span>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['search' => ''])); ?>"
                                            class="text-white ms-1 text-decoration-none" style="cursor: pointer;">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                <?php endif; ?>

                                <?php if(request()->has('status') || request()->has('search')): ?>
                                    <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-sm btn-outline-danger rounded-pill d-flex align-items-center">
                                        <i class="fas fa-times me-1"></i> <span class="d-none d-md-inline">Hapus Filter</span><span class="d-md-none">Hapus</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <!-- Success Message -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Error Message -->
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Loading State -->
                    <div id="loadingSpinner" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2">Memuat data...</p>
                    </div>

                    <!-- Bulk Actions -->
                    <?php if($clients->count() > 0): ?>
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label" for="selectAll">
                                        <small>Pilih Semua</small>
                                    </label>
                                </div>
                                <div class="btn-group me-2" id="bulkActions" style="display: none;">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi Massal
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item bulk-action-btn" href="#" data-action="activate">
                                                <i class="fas fa-check-circle text-success me-2"></i>Aktifkan
                                            </a></li>
                                        <li><a class="dropdown-item bulk-action-btn" href="#"
                                                data-action="deactivate">
                                                <i class="fas fa-times-circle text-danger me-2"></i>Nonaktifkan
                                            </a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger bulk-action-btn" href="#"
                                                data-action="delete">
                                                <i class="fas fa-trash me-2"></i>Hapus
                                            </a></li>
                                    </ul>
                                </div>
                                <small class="text-muted" id="selectedCount">0 vendor terpilih</small>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Table - Desktop view -->
                    <div class="table-responsive d-none d-lg-block" id="clientsTableContainer">
                        <table class="table table-hover align-middle" id="clientsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <?php if($clients->count() > 0): ?>
                                            <input type="checkbox" class="form-check-input" id="selectAllHeader">
                                        <?php endif; ?>
                                    </th>
                                    <th>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'name', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])); ?>"
                                            class="text-decoration-none text-dark d-flex align-items-center">
                                            <span>Nama Perusahaan</span>
                                            <?php if(request('sort') == 'name'): ?>
                                                <i
                                                    class="fas fa-sort-<?php echo e(request('order') == 'asc' ? 'up' : 'down'); ?> ms-1"></i>
                                            <?php else: ?>
                                                <i class="fas fa-sort ms-1 text-muted opacity-50"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>PIC</th>
                                    <th>
                                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'status', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])); ?>"
                                            class="text-decoration-none text-dark d-flex align-items-center">
                                            <span>Status</span>
                                            <?php if(request('sort') == 'status'): ?>
                                                <i
                                                    class="fas fa-sort-<?php echo e(request('order') == 'asc' ? 'up' : 'down'); ?> ms-1"></i>
                                            <?php else: ?>
                                                <i class="fas fa-sort ms-1 text-muted opacity-50"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Kontrak</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="client-row" data-client-id="<?php echo e($client->id); ?>">
                                        <td>
                                            <?php if($clients->count() > 0): ?>
                                                <input type="checkbox" class="form-check-input client-checkbox"
                                                    value="<?php echo e($client->id); ?>" data-client-name="<?php echo e($client->name); ?>"
                                                    data-client-email="<?php echo e($client->email); ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-start">
                                                <div class="me-2">
                                                    <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                        style="width: 36px; height: 36px; border-radius: 50%; font-size: 14px;">
                                                        <?php echo e(strtoupper(substr($client->name, 0, 2))); ?>

                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="<?php echo e(route('clients.show', $client->id)); ?>"
                                                        class="text-decoration-none text-dark">
                                                        <strong><?php echo e($client->name); ?></strong>
                                                    </a>
                                                    <?php if($client->address): ?>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-map-marker-alt fa-xs me-1"></i>
                                                            <?php echo e(Str::limit($client->address, 40)); ?>

                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="mailto:<?php echo e($client->email); ?>" class="text-decoration-none">
                                                <i class="fas fa-envelope fa-sm me-1 text-muted"></i>
                                                <span class="text-dark"><?php echo e($client->email); ?></span>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if($client->phone): ?>
                                                <a href="tel:<?php echo e($client->phone); ?>" class="text-decoration-none">
                                                    <i class="fas fa-phone fa-sm me-1 text-muted"></i>
                                                    <span class="text-dark"><?php echo e($client->phone); ?></span>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($client->pic_name): ?>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-tie text-info me-1"></i>
                                                    <span><?php echo e($client->pic_name); ?></span>
                                                </div>
                                                <?php if($client->pic_phone || $client->pic_email): ?>
                                                    <small class="text-muted d-block mt-1">
                                                        <?php if($client->pic_phone): ?>
                                                            <i
                                                                class="fas fa-mobile-alt fa-xs me-1"></i><?php echo e($client->pic_phone); ?>

                                                        <?php endif; ?>
                                                        <?php if($client->pic_email): ?>
                                                            <br><i
                                                                class="fas fa-envelope fa-xs me-1"></i><?php echo e($client->pic_email); ?>

                                                        <?php endif; ?>
                                                    </small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($client->status == 'active'): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i> Non-Aktif
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $contractCount =
                                                    $client->contracts_count ?? $client->contracts->count();
                                            ?>
                                            <?php if($contractCount > 0): ?>
                                                <a href="<?php echo e(route('contracts.index', ['client_id' => $client->id])); ?>"
                                                    class="badge bg-primary text-decoration-none" title="Lihat kontrak"
                                                    data-bs-toggle="tooltip">
                                                    <i class="fas fa-file-contract me-1"></i> <?php echo e($contractCount); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('clients.show', $client->id)); ?>" class="btn btn-info"
                                                    title="Detail" data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('clients.edit', $client->id)); ?>"
                                                    class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger delete-client-btn"
                                                    data-client-id="<?php echo e($client->id); ?>"
                                                    data-client-name="<?php echo e($client->name); ?>"
                                                    data-contracts-count="<?php echo e($contractCount); ?>" title="Hapus"
                                                    data-bs-toggle="tooltip">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                                                <h4 class="h5">Tidak ada data vendor</h4>
                                                <p class="mb-3">Mulai dengan menambahkan vendor baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="d-lg-none">
                        <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="vendor-card mb-3" data-client-id="<?php echo e($client->id); ?>">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <!-- Header -->
                                        <div class="d-flex align-items-start justify-content-between mb-3">
                                            <div class="d-flex align-items-start flex-grow-1">
                                                <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                                    style="width: 48px; height: 48px; border-radius: 50%; font-size: 16px; flex-shrink: 0;">
                                                    <?php echo e(strtoupper(substr($client->name, 0, 2))); ?>

                                                </div>
                                                <div class="flex-grow-1">
                                                    <a href="<?php echo e(route('clients.show', $client->id)); ?>"
                                                        class="text-decoration-none text-dark">
                                                        <h6 class="mb-1 fw-bold"><?php echo e($client->name); ?></h6>
                                                    </a>
                                                    <?php if($client->address): ?>
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-map-marker-alt fa-xs me-1"></i>
                                                            <?php echo e(Str::limit($client->address, 40)); ?>

                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <input type="checkbox" class="form-check-input client-checkbox"
                                                    value="<?php echo e($client->id); ?>" data-client-name="<?php echo e($client->name); ?>"
                                                    data-client-email="<?php echo e($client->email); ?>" style="transform: scale(1.2);">
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="mb-3">
                                            <?php if($client->status == 'active'): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i> Non-Aktif
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Contact Info -->
                                        <div class="contact-info mb-3">
                                            <!-- Email -->
                                            <a href="mailto:<?php echo e($client->email); ?>" class="text-decoration-none text-dark mb-2 d-flex align-items-center">
                                                <i class="fas fa-envelope fa-sm me-2 text-muted" style="width: 20px;"></i>
                                                <span class="small"><?php echo e($client->email); ?></span>
                                            </a>

                                            <!-- Phone -->
                                            <?php if($client->phone): ?>
                                                <a href="tel:<?php echo e($client->phone); ?>" class="text-decoration-none text-dark mb-2 d-flex align-items-center">
                                                    <i class="fas fa-phone fa-sm me-2 text-muted" style="width: 20px;"></i>
                                                    <span class="small"><?php echo e($client->phone); ?></span>
                                                </a>
                                            <?php endif; ?>

                                            <!-- PIC -->
                                            <?php if($client->pic_name): ?>
                                                <div class="text-dark mb-2 d-flex align-items-center">
                                                    <i class="fas fa-user-tie fa-sm me-2 text-info" style="width: 20px;"></i>
                                                    <span class="small">
                                                        <strong><?php echo e($client->pic_name); ?></strong>
                                                        <?php if($client->pic_phone): ?>
                                                            <br><span class="text-muted small"><?php echo e($client->pic_phone); ?></span>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Contracts Count -->
                                        <?php
                                            $contractCount = $client->contracts_count ?? $client->contracts->count();
                                        ?>
                                        <?php if($contractCount > 0): ?>
                                            <a href="<?php echo e(route('contracts.index', ['client_id' => $client->id])); ?>"
                                                class="badge bg-primary text-decoration-none mb-3 d-inline-block">
                                                <i class="fas fa-file-contract me-1"></i> <?php echo e($contractCount); ?> Kontrak
                                            </a>
                                        <?php else: ?>
                                            <div class="badge bg-secondary mb-3">
                                                <i class="fas fa-file-contract me-1"></i> Tidak ada Kontrak
                                            </div>
                                        <?php endif; ?>

                                        <!-- Action Buttons -->
                                        <div class="d-grid gap-2">
                                            <a href="<?php echo e(route('clients.show', $client->id)); ?>" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                            <a href="<?php echo e(route('clients.edit', $client->id)); ?>"
                                                class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-client-btn"
                                                data-client-id="<?php echo e($client->id); ?>"
                                                data-client-name="<?php echo e($client->name); ?>"
                                                data-contracts-count="<?php echo e($contractCount); ?>">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                                    <h4 class="h5">Tidak ada data vendor</h4>
                                    <p class="mb-3 small">Mulai dengan menambahkan vendor baru</p>
                                    <a href="<?php echo e(route('clients.create')); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i> Tambah Vendor
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if($clients->total() > $clients->perPage()): ?>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-2 px-2 px-md-0">
                            <div class="text-center text-md-start">
                                <small class="text-muted">
                                    Menampilkan <strong><?php echo e($clients->firstItem() ?? 0); ?>-<?php echo e($clients->lastItem() ?? 0); ?></strong> dari
                                    <strong><?php echo e($clients->total()); ?></strong> vendor
                                </small>
                            </div>
                            <nav aria-label="Page navigation" class="w-100 w-md-auto">
                                <!-- Desktop pagination -->
                                <div class="d-none d-md-block">
                                    <?php echo e($clients->appends(request()->except('page'))->links('pagination::bootstrap-5')); ?>

                                </div>

                                <!-- Mobile compact pagination -->
                                <div class="d-md-none">
                                    <ul class="pagination pagination-sm justify-content-center mb-0">
                                        
                                        <?php if($clients->onFirstPage()): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e($clients->previousPageUrl()); ?>"
                                                    aria-label="Previous">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <?php echo e($clients->currentPage()); ?> / <?php echo e($clients->lastPage()); ?>

                                            </span>
                                        </li>

                                        
                                        <?php if($clients->hasMorePages()): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e($clients->nextPageUrl()); ?>"
                                                    aria-label="Next">
                                                    <i class="fas fa-chevron-right"></i>
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 px-2 px-md-0">
                        <small class="text-muted text-center text-md-start">
                            <i class="fas fa-info-circle me-1"></i>
                            Total: <strong><?php echo e($clients->total()); ?></strong> vendor
                            <?php if($clients->total() > 0): ?>
                                • <span class="text-success">Aktif: <?php echo e($activeCount ?? 0); ?></span>
                                • <span class="text-danger">Non-Aktif: <?php echo e($inactiveCount ?? 0); ?></span>
                            <?php endif; ?>
                        </small>
                        <div class="d-flex align-items-center gap-2 w-100 w-md-auto justify-content-center justify-content-md-end">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteClientModal" tabindex="-1" aria-labelledby="deleteClientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteClientModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus vendor <strong id="clientNameToDelete"></strong>?</p>
                    <div class="alert alert-warning" id="contractWarning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Peringatan:</strong> Data yang sudah dihapus tidak dapat dikembalikan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteClientBtn">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteClientForm" method="POST" action="" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bulkActionModalLabel">
                        <i class="fas fa-tasks me-2"></i> Aksi Massal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin <span id="bulkActionText"></span> <strong id="bulkActionCount"></strong>
                        vendor terpilih?</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Aksi ini akan diterapkan pada semua vendor yang dipilih.
                        <div id="bulkActionList" class="mt-2 small"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    
                    <form id="bulkActionForm" method="POST" action="<?php echo e(route('clients.bulk-actions')); ?>"
                        style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <div id="bulkActionClientIdsContainer"></div>
                        <input type="hidden" name="action" id="bulkActionType">
                        <button type="submit" class="btn btn-primary" id="bulkActionConfirmBtn">
                            <i class="fas fa-check me-1"></i> Konfirmasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Search functionality - responsive
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#clientsTable tbody tr.client-row');
            const vendorCards = document.querySelectorAll('.vendor-card');

            function performSearch(filter) {
                let visibleCount = 0;

                // Desktop table search
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (filter === '' || text.includes(filter)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Mobile card search
                vendorCards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    if (filter === '' || text.includes(filter)) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no data message
                const noDataRow = document.querySelector('#clientsTable tbody tr:not(.client-row)');
                if (noDataRow) {
                    noDataRow.style.display = visibleCount === 0 ? '' : 'none';
                }

                return visibleCount;
            }

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const filter = this.value.toLowerCase().trim();
                    performSearch(filter);

                    // Update URL without reloading
                    const url = new URL(window.location);
                    if (filter) {
                        url.searchParams.set('search', filter);
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.history.replaceState({}, '', url);
                });

                // Auto-focus on page load
                searchInput.focus();
            }

            // Mobile card click handler - navigate to detail
            vendorCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    // Don't navigate if clicking on button or checkbox
                    if (e.target.closest('.btn') || e.target.matches('input[type="checkbox"]')) {
                        return;
                    }
                    const clientId = this.getAttribute('data-client-id');
                    if (clientId) {
                        window.location.href = `/clients/${clientId}`;
                    }
                });
            });

            // Delete client modal
            const deleteClientModal = new bootstrap.Modal(document.getElementById('deleteClientModal'));
            const deleteClientForm = document.getElementById('deleteClientForm');
            let deleteClientAction = null;
            const clientNameToDelete = document.getElementById('clientNameToDelete');
            const contractWarning = document.getElementById('contractWarning');

            document.querySelectorAll('.delete-client-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent card click navigation
                    const clientId = this.getAttribute('data-client-id');
                    const clientName = this.getAttribute('data-client-name');
                    const contractsCount = this.getAttribute('data-contracts-count');

                    clientNameToDelete.textContent = clientName;
                    deleteClientAction = `/clients/${clientId}`;

                    // Update warning if client has contracts
                    if (contractsCount > 0) {
                        contractWarning.innerHTML = `
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Peringatan:</strong> Vendor ini memiliki ${contractsCount} kontrak yang terkait.
                            <br><small>Menghapus vendor akan mempengaruhi data kontrak terkait.</small>
                        `;
                    } else {
                        contractWarning.innerHTML = `
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Peringatan:</strong> Data yang sudah dihapus tidak dapat dikembalikan.
                        `;
                    }

                    deleteClientModal.show();
                });
            });

            // Confirm deletion via fetch
            document.getElementById('confirmDeleteClientBtn').addEventListener('click', function() {
                if (!deleteClientAction || !deleteClientForm) return;
                if (!confirm('Hapus vendor ini? Tindakan ini tidak dapat dibatalkan.')) return;

                deleteClientForm.action = deleteClientAction;
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menghapus...';
                deleteClientForm.submit();
            });

            // Bulk selection functionality
            const selectAllHeader = document.getElementById('selectAllHeader');
            const selectAll = document.getElementById('selectAll');
            const clientCheckboxes = document.querySelectorAll('.client-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            const bulkActionModal = new bootstrap.Modal(document.getElementById('bulkActionModal'));

            function updateSelection() {
                const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');
                const count = checkedBoxes.length;

                selectedCount.textContent = `${count} vendor terpilih`;

                // Show/hide bulk actions
                if (count > 0) {
                    bulkActions.style.display = 'block';
                } else {
                    bulkActions.style.display = 'none';
                }

                // Update select all checkboxes
                const allChecked = count === clientCheckboxes.length && clientCheckboxes.length > 0;
                if (selectAllHeader) selectAllHeader.checked = allChecked;
                if (selectAll) selectAll.checked = allChecked;

                // Set indeterminate state
                if (selectAllHeader) {
                    selectAllHeader.indeterminate = count > 0 && count < clientCheckboxes.length;
                }
            }

            if (selectAllHeader) {
                selectAllHeader.addEventListener('change', function() {
                    const isChecked = this.checked;
                    clientCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                    updateSelection();
                });
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const isChecked = this.checked;
                    clientCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                    updateSelection();
                });
            }

            clientCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelection);
            });

            // Bulk actions
            document.querySelectorAll('.bulk-action-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.getAttribute('data-action');
                    const checkedBoxes = document.querySelectorAll('.client-checkbox:checked');

                    if (checkedBoxes.length === 0) {
                        showToast('Pilih setidaknya satu vendor', 'warning');
                        return;
                    }

                    const clientIds = [];
                    const clientNames = [];
                    const clientEmails = [];

                    checkedBoxes.forEach(checkbox => {
                        clientIds.push(checkbox.value);
                        clientNames.push(checkbox.getAttribute('data-client-name'));
                        clientEmails.push(checkbox.getAttribute('data-client-email'));
                    });

                    // Set modal content based on action
                    let actionText = '';
                    let actionBtnText = '';
                    let modalColor = 'primary';

                    switch (action) {
                        case 'activate':
                            actionText = 'mengaktifkan';
                            actionBtnText = 'Aktifkan';
                            break;
                        case 'deactivate':
                            actionText = 'menonaktifkan';
                            actionBtnText = 'Nonaktifkan';
                            modalColor = 'warning';
                            break;
                        case 'delete':
                            actionText = 'menghapus';
                            actionBtnText = 'Hapus';
                            modalColor = 'danger';
                            break;
                    }

                    // Update modal
                    document.getElementById('bulkActionText').textContent = actionText;
                    document.getElementById('bulkActionCount').textContent = clientIds.length;
                    document.getElementById('bulkActionType').value = action;

                    const clientIdsContainer = document.getElementById('bulkActionClientIdsContainer');
                    if (clientIdsContainer) {
                        clientIdsContainer.innerHTML = '';
                        clientIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'client_ids[]';
                            input.value = id;
                            clientIdsContainer.appendChild(input);
                        });
                    }

                    // Update modal header color
                    const modalHeader = document.querySelector('#bulkActionModal .modal-header');
                    modalHeader.className = `modal-header bg-${modalColor} text-white`;

                    // Update confirm button
                    const confirmBtn = document.getElementById('bulkActionConfirmBtn');
                    confirmBtn.className = `btn btn-${modalColor}`;
                    confirmBtn.innerHTML = `<i class="fas fa-check me-1"></i> ${actionBtnText}`;

                    // Update list
                    const actionList = document.getElementById('bulkActionList');
                    actionList.innerHTML = '';
                    clientNames.slice(0, 3).forEach((name, index) => {
                        actionList.innerHTML +=
                            `<div>• ${name} <small class="text-muted">(${clientEmails[index]})</small></div>`;
                    });
                    if (clientNames.length > 3) {
                        actionList.innerHTML +=
                            `<div class="text-muted">... dan ${clientNames.length - 3} lainnya</div>`;
                    }

                    bulkActionModal.show();
                });
            });

            // Toast notification
            function showToast(message, type = 'success') {
                const toastContainer = document.createElement('div');
                toastContainer.className = 'position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '1060';

                const bgColor = type === 'success' ? 'bg-success' :
                    type === 'error' ? 'bg-danger' :
                    type === 'warning' ? 'bg-warning' : 'bg-info';

                const iconClass = type === 'success' ? 'check-circle' :
                    type === 'error' ? 'exclamation-circle' :
                    type === 'warning' ? 'exclamation-triangle' : 'info-circle';

                const title = type === 'success' ? 'Sukses' :
                    type === 'error' ? 'Error' :
                    type === 'warning' ? 'Peringatan' : 'Info';

                toastContainer.innerHTML = `
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header ${bgColor} text-white">
                            <i class="fas fa-${iconClass} me-2"></i>
                            <strong class="me-auto">${title}</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">${message}</div>
                    </div>
                `;

                document.body.appendChild(toastContainer);

                setTimeout(() => {
                    toastContainer.remove();
                }, 3000);
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + K for search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }

                // Ctrl/Cmd + N for new vendor
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = "<?php echo e(route('clients.create')); ?>";
                }
            });

            // Handle bulk action form submission
            document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalHtml = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
                submitBtn.disabled = true;
            });

            // Prevent touch callout on iOS
            document.addEventListener('touchmove', function(e) {
                // Allow scrolling but prevent callout
            }, { passive: true });
        });
    </script>

    <style>
        /* Gradient header */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%) !important;
        }

        /* Avatar circle */
        .avatar-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            background: linear-gradient(135deg, #0d6efd, #0765e8);
            color: white;
        }

        /* Table row hover effect */
        .client-row:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transition: background-color 0.2s ease;
        }

        /* Vendor card styles */
        .vendor-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .vendor-card:active {
            transform: scale(0.98);
        }

        .vendor-card .card {
            border-top: 3px solid #0d6efd;
        }

        .vendor-card:active .card {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        /* Contact info styling */
        .contact-info a,
        .contact-info div {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 0.75rem;
        }

        .contact-info a:last-child,
        .contact-info div:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .contact-info a:hover {
            background-color: #f8f9fa;
            margin-left: -0.5rem;
            padding-left: 0.5rem;
            margin-right: -0.5rem;
            padding-right: 0.5rem;
            transition: all 0.2s ease;
        }

        /* Button styles */
        .btn-group .btn {
            border-radius: 0.25rem;
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        /* Dropdown styling */
        .dropdown-menu {
            max-height: 70vh;
            overflow-y: auto;
        }

        .dropdown-item.active {
            background-color: #0d6efd;
            color: white;
        }

        /* Pagination */
        .pagination .page-link {
            border-radius: 0.25rem;
            margin: 0 2px;
        }

        /* Checkbox styling */
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Table thead styling */
        .table thead th {
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .table tbody td {
            vertical-align: middle;
            border-bottom: 1px solid #eef1f5;
        }

        /* Badge styling */
        .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
        }

        /* Desktop view */
        @media (min-width: 992px) {
            .vendor-header-wrapper {
                flex-direction: row;
                align-items: center;
            }

            .vendor-header-controls {
                margin-left: auto;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }

        /* Tablet view */
        @media (max-width: 991px) and (min-width: 577px) {
            .card-header {
                padding: 1rem !important;
            }

            .vendor-header-wrapper {
                flex-direction: column;
                align-items: stretch;
            }

            .vendor-header-controls {
                margin-top: 0.75rem;
            }

            .vendor-header-controls > * {
                flex-grow: 1;
            }

            .vendor-header-controls .dropdown {
                width: auto;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .avatar-circle {
                width: 32px !important;
                height: 32px !important;
                font-size: 11px !important;
            }

            .table td,
            .table th {
                padding: 0.5rem 0.75rem;
            }

            .btn-group-sm > .btn {
                padding: 0.3rem 0.5rem;
                font-size: 0.8rem;
            }
        }

        /* Mobile view */
        @media (max-width: 576px) {
            .card {
                margin-bottom: 0;
            }

            .card-header {
                padding: 0.75rem;
            }

            .card-body {
                padding: 1rem;
            }

            .card-footer {
                padding: 0.75rem;
            }

            .vendor-header-title h5 {
                font-size: 1rem;
            }

            .vendor-header-title small {
                font-size: 0.75rem;
            }

            .vendor-header-controls {
                flex-direction: column;
                gap: 0.5rem;
                margin-top: 0.75rem;
            }

            .vendor-header-controls > * {
                width: 100%;
            }

            .vendor-header-controls .input-group {
                flex-grow: 1;
            }

            .vendor-header-controls .dropdown .btn {
                width: 100%;
                justify-content: center;
            }

            .vendor-card .card {
                margin-bottom: 1rem;
                transition: all 0.2s ease;
            }

            .vendor-card:active .card {
                transform: translateY(-4px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            }

            .contact-info a,
            .contact-info div {
                padding: 0.5rem 0;
                font-size: 0.9rem;
            }

            .contact-info i {
                font-size: 0.85rem;
            }

            .btn-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .badge {
                font-size: 0.75rem;
                padding: 0.35rem 0.5rem;
                display: inline-block;
            }

            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-body {
                padding: 1rem;
            }

            /* Compact header on mobile */
            .bg-white.bg-opacity-25 {
                width: 36px;
                height: 36px;
            }

            /* Improve search input on mobile */
            .input-group input {
                font-size: 16px; /* Prevent zoom on iOS */
            }

            /* More compact pagination on mobile */
            .pagination-sm .page-link {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }

            /* Improve filter chip spacing */
            .badge {
                margin-bottom: 0.25rem;
            }

            /* Better tap targets on mobile */
            .btn-sm {
                min-height: 44px; /* Recommended min touch target */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .form-check-input {
                width: 1.2em;
                height: 1.2em;
                margin-top: 0.15em;
                cursor: pointer;
            }
        }

        /* Extra small devices */
        @media (max-width: 360px) {
            .vendor-header-title h5 {
                font-size: 0.95rem;
            }

            .contact-info a,
            .contact-info div {
                font-size: 0.85rem;
            }

            .badge {
                font-size: 0.7rem;
                padding: 0.3rem 0.4rem;
            }

            .btn-outline-info,
            .btn-outline-warning,
            .btn-outline-danger {
                font-size: 0.8rem;
                padding: 0.4rem 0.6rem;
            }
        }

        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        a, button {
            -webkit-user-select: none;
            user-select: none;
        }

        /* Prevent double tap zoom on mobile */
        input,
        button,
        a,
        select,
        textarea {
            -webkit-touch-callout: none;
        }

        /* Loading animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .loading-row {
            animation: pulse 1.5s infinite;
        }

        /* Better search input focus */
        .input-group-text,
        .form-control {
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views\clients\index.blade.php ENDPATH**/ ?>