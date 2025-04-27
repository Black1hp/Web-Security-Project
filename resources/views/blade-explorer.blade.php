<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blade Files Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .blade-file {
            padding: 15px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        .blade-file:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
            text-decoration: none;
            color: inherit;
        }
        .blade-path {
            color: #6c757d;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .blade-name {
            font-weight: bold;
            color: #0d6efd;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-box {
            margin-bottom: 20px;
            position: relative;
        }
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .search-input {
            padding-left: 40px;
        }
        .file-icon {
            color: #0d6efd;
        }
        .folder-icon {
            color: #6c757d;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Blade Files Explorer</h1>
            <a href="{{ route('blade-explorer') }}" class="btn btn-primary">
                <i class="fas fa-sync"></i> Refresh
            </a>
        </div>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Search blade files...">
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="bladeFilesList">
                    @foreach($bladeFiles as $file)
                    <a href="{{ route('blade-viewer', ['path' => ltrim($file['path'], '/')]) }}" class="blade-file" data-path="{{ $file['path'] }}" data-name="{{ $file['name'] }}">
                        <div class="blade-name">
                            <i class="fas fa-file-code file-icon"></i>
                            {{ $file['name'] }}
                        </div>
                        <div class="blade-path">
                            <i class="fas fa-folder folder-icon"></i>
                            {{ $file['path'] }}
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const files = document.querySelectorAll('.blade-file');
            
            files.forEach(file => {
                const path = file.dataset.path.toLowerCase();
                const name = file.dataset.name.toLowerCase();
                
                if (path.includes(searchText) || name.includes(searchText)) {
                    file.style.display = 'block';
                } else {
                    file.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html> 