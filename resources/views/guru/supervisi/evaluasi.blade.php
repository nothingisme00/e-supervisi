@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Progress Bar -->
            <div class="progress mb-4" style="height: 25px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 50%">
                    Tahap 1: Lembar Evaluasi Diri
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="supervisiTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="evaluasi-tab" data-bs-toggle="tab" 
                            data-bs-target="#evaluasi" type="button">
                        <i class="bi bi-file-earmark-text"></i> Lembar Evaluasi Diri
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link disabled" id="proses-tab" data-bs-toggle="tab" 
                            data-bs-target="#proses" type="button">
                        <i class="bi bi-play-circle"></i> Proses
                        <span class="badge bg-secondary ms-2" id="documentBadge">0/7</span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="supervisiTabContent">
                <div class="tab-pane fade show active" id="evaluasi" role="tabpanel">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Upload Dokumen Evaluasi Diri</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                Upload 7 dokumen yang diperlukan. Format yang diperbolehkan: PDF, JPG, PNG (Max: 2MB)
                            </div>

                            <div class="row" id="documentList">
                                <!-- Documents will be loaded here -->
                            </div>

                            <div class="text-end mt-4">
                                <button class="btn btn-success" id="nextButton" disabled>
                                    Next <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal Template -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="jenis_dokumen" name="jenis_dokumen">
                    <div class="mb-3">
                        <label class="form-label">Pilih File</label>
                        <input type="file" class="form-control" name="file" required 
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Format: PDF, JPG, PNG | Max: 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const supervisiId = {{ $supervisi->id }};
const documents = {
    'rpp': 'Rencana Pelaksanaan Pembelajaran (RPP)',
    'silabus': 'Silabus',
    'prota': 'Program Tahunan (PROTA)',
    'prosem': 'Program Semester (PROSEM)',
    'kkm': 'Kriteria Ketuntasan Minimal (KKM)',
    'jadwal': 'Jadwal Mengajar',
    'absensi': 'Daftar Hadir Siswa'
};

const uploadedDocs = @json($uploadedDocuments);

// Initialize document list
function initDocumentList() {
    const container = document.getElementById('documentList');
    container.innerHTML = '';
    
    Object.keys(documents).forEach(key => {
        const isUploaded = uploadedDocs.includes(key);
        const card = `
            <div class="col-md-6 mb-3">
                <div class="card ${isUploaded ? 'border-success' : ''}">
                    <div class="card-body">
                        <h6>${documents[key]}</h6>
                        <div class="mt-2">
                            ${isUploaded ? 
                                `<span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Sudah diupload
                                </span>
                                <button class="btn btn-sm btn-danger ms-2" onclick="deleteDocument('${key}')">
                                    <i class="bi bi-trash"></i>
                                </button>` :
                                `<button class="btn btn-sm btn-primary" onclick="openUploadModal('${key}')">
                                    <i class="bi bi-upload"></i> Upload
                                </button>`
                            }
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
    
    updateProgress();
}

function openUploadModal(jenis) {
    document.getElementById('jenis_dokumen').value = jenis;
    document.querySelector('#uploadModal .modal-title').textContent = 'Upload ' + documents[jenis];
    new bootstrap.Modal(document.getElementById('uploadModal')).show();
}

function updateProgress() {
    const uploaded = uploadedDocs.length;
    const badge = document.getElementById('documentBadge');
    badge.textContent = `${uploaded}/7`;
    
    if (uploaded >= 7) {
        badge.classList.remove('bg-secondary');
        badge.classList.add('bg-success');
        document.getElementById('nextButton').disabled = false;
        document.getElementById('proses-tab').classList.remove('disabled');
    } else {
        badge.classList.add('bg-secondary');
        badge.classList.remove('bg-success');
        document.getElementById('nextButton').disabled = true;
        document.getElementById('proses-tab').classList.add('disabled');
    }
}

// Upload form submission
document.getElementById('uploadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    formData.append('_token', '{{ csrf_token() }}');
    
    try {
        const response = await fetch(`/guru/supervisi/${supervisiId}/upload`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            const jenis = document.getElementById('jenis_dokumen').value;
            uploadedDocs.push(jenis);
            initDocumentList();
            bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
            e.target.reset();
            
            // Show success message
            alert('Dokumen berhasil diupload!');
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

async function deleteDocument(jenis) {
    if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
        return;
    }
    
    try {
        const response = await fetch(`/guru/supervisi/${supervisiId}/delete-document`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ jenis_dokumen: jenis })
        });
        
        const result = await response.json();
        
        if (result.success) {
            const index = uploadedDocs.indexOf(jenis);
            if (index > -1) {
                uploadedDocs.splice(index, 1);
            }
            initDocumentList();
            alert('Dokumen berhasil dihapus!');
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
}

// Next button
document.getElementById('nextButton').addEventListener('click', () => {
    window.location.href = `/guru/supervisi/${supervisiId}/proses`;
});

// Initialize on page load
initDocumentList();
</script>
@endsection