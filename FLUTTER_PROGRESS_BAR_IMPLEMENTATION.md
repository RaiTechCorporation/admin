# Flutter Progress Bar Implementation Guide

Complete guide to add upload progress bars in your Flutter Groovsta app.

---

## 1. Setup & Dependencies

### Add to `pubspec.yaml`

```yaml
dependencies:
  flutter:
    sdk: flutter
  dio: ^5.3.1
  http: ^1.1.0
  video_player: ^2.7.0
  image_picker: ^1.0.0
  percent_indicator: ^4.1.1  # Optional: for circular progress

dev_dependencies:
  flutter_test:
    sdk: flutter
```

Run:
```bash
flutter pub get
```

---

## 2. Basic Progress Bar Widgets

### Option 1: Linear Progress Bar (Built-in)

```dart
import 'package:flutter/material.dart';

class BasicLinearProgress extends StatefulWidget {
  @override
  State<BasicLinearProgress> createState() => _BasicLinearProgressState();
}

class _BasicLinearProgressState extends State<BasicLinearProgress> {
  double _progress = 0.0;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        // Simple linear progress bar
        LinearProgressIndicator(
          value: _progress,
          minHeight: 8,
          backgroundColor: Colors.grey[300],
          valueColor: AlwaysStoppedAnimation<Color>(Colors.blue),
        ),
        SizedBox(height: 8),
        
        // With percentage text
        Stack(
          alignment: Alignment.center,
          children: [
            LinearProgressIndicator(
              value: _progress,
              minHeight: 20,
              backgroundColor: Colors.grey[300],
              valueColor: AlwaysStoppedAnimation<Color>(Colors.green),
            ),
            Text(
              '${(_progress * 100).toStringAsFixed(0)}%',
              style: TextStyle(
                color: Colors.black,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ],
    );
  }
}
```

### Option 2: Circular Progress Bar

```dart
import 'package:flutter/material.dart';

class CircularProgressWidget extends StatefulWidget {
  @override
  State<CircularProgressWidget> createState() => _CircularProgressWidgetState();
}

class _CircularProgressWidgetState extends State<CircularProgressWidget> {
  double _progress = 0.0;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Stack(
        alignment: Alignment.center,
        children: [
          SizedBox(
            height: 120,
            width: 120,
            child: CircularProgressIndicator(
              value: _progress,
              strokeWidth: 6,
              backgroundColor: Colors.grey[300],
              valueColor: AlwaysStoppedAnimation<Color>(Colors.blue),
            ),
          ),
          Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(
                '${(_progress * 100).toStringAsFixed(1)}%',
                style: TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.bold,
                ),
              ),
              Text(
                'Uploading',
                style: TextStyle(fontSize: 12, color: Colors.grey),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
```

### Option 3: Custom Gradient Progress Bar

```dart
import 'package:flutter/material.dart';

class GradientProgressBar extends StatefulWidget {
  final double progress;
  final String label;

  const GradientProgressBar({
    required this.progress,
    this.label = '',
  });

  @override
  State<GradientProgressBar> createState() => _GradientProgressBarState();
}

class _GradientProgressBarState extends State<GradientProgressBar> {
  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        ClipRRect(
          borderRadius: BorderRadius.circular(10),
          child: Stack(
            children: [
              Container(
                height: 12,
                color: Colors.grey[300],
              ),
              Container(
                height: 12,
                width: MediaQuery.of(context).size.width * widget.progress,
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [Colors.blue, Colors.cyan],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
              ),
            ],
          ),
        ),
        SizedBox(height: 8),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              widget.label,
              style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500),
            ),
            Text(
              '${(widget.progress * 100).toStringAsFixed(1)}%',
              style: TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.bold,
                color: Colors.blue,
              ),
            ),
          ],
        ),
      ],
    );
  }
}
```

---

## 3. Upload Service with Progress Tracking

### API Service Class

```dart
import 'package:dio/dio.dart';
import 'dart:io';

class UploadService {
  final Dio _dio = Dio();
  final String _baseUrl = 'https://groovsta-bucket.s3.ap-south-1.amazonaws.com';
  final String _apiUrl = 'https://your-api.com/api';
  final String _apiKey = 'retry123';

  /// Upload video with progress tracking
  Future<Map<String, dynamic>> uploadVideo({
    required File videoFile,
    required String authToken,
    required Function(int sent, int total) onProgress,
  }) async {
    try {
      FormData formData = FormData.fromMap({
        'video': await MultipartFile.fromFile(
          videoFile.path,
          filename: videoFile.path.split('/').last,
        ),
      });

      Response response = await _dio.post(
        '$_apiUrl/post/uploadVideo',
        data: formData,
        options: Options(
          headers: {
            'APIKEY': _apiKey,
            'AUTHTOKEN': authToken,
            'Content-Type': 'multipart/form-data',
          },
          receiveTimeout: Duration(seconds: 300),
          sendTimeout: Duration(seconds: 300),
        ),
        onSendProgress: (int sent, int total) {
          print('Video Upload Progress: $sent / $total');
          onProgress(sent, total);
        },
      );

      if (response.statusCode == 200) {
        return {
          'success': true,
          'data': response.data['data'],
          'message': response.data['message'],
        };
      } else {
        return {
          'success': false,
          'message': 'Upload failed with status ${response.statusCode}',
        };
      }
    } on DioException catch (e) {
      return {
        'success': false,
        'message': 'Error: ${e.message}',
        'error': e,
      };
    }
  }

  /// Upload thumbnail with progress tracking
  Future<Map<String, dynamic>> uploadThumbnail({
    required File imageFile,
    required String authToken,
    required Function(int sent, int total) onProgress,
  }) async {
    try {
      FormData formData = FormData.fromMap({
        'thumbnail': await MultipartFile.fromFile(
          imageFile.path,
          filename: imageFile.path.split('/').last,
        ),
      });

      Response response = await _dio.post(
        '$_apiUrl/post/uploadThumbnail',
        data: formData,
        options: Options(
          headers: {
            'APIKEY': _apiKey,
            'AUTHTOKEN': authToken,
            'Content-Type': 'multipart/form-data',
          },
          receiveTimeout: Duration(seconds: 60),
          sendTimeout: Duration(seconds: 60),
        ),
        onSendProgress: (int sent, int total) {
          print('Thumbnail Upload Progress: $sent / $total');
          onProgress(sent, total);
        },
      );

      if (response.statusCode == 200) {
        return {
          'success': true,
          'data': response.data['data'],
          'message': response.data['message'],
        };
      } else {
        return {
          'success': false,
          'message': 'Upload failed with status ${response.statusCode}',
        };
      }
    } on DioException catch (e) {
      return {
        'success': false,
        'message': 'Error: ${e.message}',
        'error': e,
      };
    }
  }

  /// Format bytes to human readable format
  static String formatBytes(int bytes) {
    if (bytes <= 0) return "0 B";
    const suffixes = ["B", "KB", "MB", "GB"];
    var i = (Math.log(bytes) / Math.log(1024)).floor();
    return '${(bytes / Math.pow(1024, i)).toStringAsFixed(2)} ${suffixes[i]}';
  }
}
```

---

## 4. Complete Upload Widget with Progress Bar

### Video Upload Widget

```dart
import 'package:flutter/material.dart';
import 'dart:io';
import 'package:image_picker/image_picker.dart';

class VideoUploadWidget extends StatefulWidget {
  final String authToken;
  final Function(String videoUrl, String thumbnailUrl)? onUploadComplete;

  const VideoUploadWidget({
    required this.authToken,
    this.onUploadComplete,
  });

  @override
  State<VideoUploadWidget> createState() => _VideoUploadWidgetState();
}

class _VideoUploadWidgetState extends State<VideoUploadWidget> {
  final ImagePicker _picker = ImagePicker();
  final UploadService _uploadService = UploadService();

  File? _videoFile;
  File? _thumbnailFile;
  double _videoProgress = 0.0;
  double _thumbnailProgress = 0.0;
  bool _uploading = false;
  String _statusMessage = '';

  /// Pick video from gallery
  Future<void> _pickVideo() async {
    final XFile? video = await _picker.pickVideo(
      source: ImageSource.gallery,
    );

    if (video != null) {
      setState(() {
        _videoFile = File(video.path);
        _statusMessage = 'Video selected: ${video.name}';
      });
    }
  }

  /// Pick thumbnail from gallery
  Future<void> _pickThumbnail() async {
    final XFile? image = await _picker.pickImage(
      source: ImageSource.gallery,
    );

    if (image != null) {
      setState(() {
        _thumbnailFile = File(image.path);
        _statusMessage = 'Thumbnail selected: ${image.name}';
      });
    }
  }

  /// Upload both video and thumbnail
  Future<void> _uploadFiles() async {
    if (_videoFile == null || _thumbnailFile == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Please select both video and thumbnail')),
      );
      return;
    }

    setState(() {
      _uploading = true;
      _statusMessage = 'Starting upload...';
    });

    try {
      // Upload video
      setState(() => _statusMessage = 'Uploading video...');
      final videoResult = await _uploadService.uploadVideo(
        videoFile: _videoFile!,
        authToken: widget.authToken,
        onProgress: (sent, total) {
          setState(() {
            _videoProgress = sent / total;
          });
        },
      );

      if (!videoResult['success']) {
        throw Exception(videoResult['message']);
      }

      String videoUrl = videoResult['data']['video'];

      // Upload thumbnail
      setState(() => _statusMessage = 'Uploading thumbnail...');
      final thumbnailResult = await _uploadService.uploadThumbnail(
        imageFile: _thumbnailFile!,
        authToken: widget.authToken,
        onProgress: (sent, total) {
          setState(() {
            _thumbnailProgress = sent / total;
          });
        },
      );

      if (!thumbnailResult['success']) {
        throw Exception(thumbnailResult['message']);
      }

      String thumbnailUrl = thumbnailResult['data']['thumbnail'];

      setState(() {
        _statusMessage = 'Upload completed successfully!';
        _uploading = false;
      });

      // Callback
      widget.onUploadComplete?.call(videoUrl, thumbnailUrl);

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Files uploaded successfully!'),
          backgroundColor: Colors.green,
        ),
      );
    } catch (e) {
      setState(() {
        _statusMessage = 'Upload failed: $e';
        _uploading = false;
      });

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Upload failed: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // Video Selection
          Card(
            child: Padding(
              padding: EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Select Video',
                    style: Theme.of(context).textTheme.titleMedium,
                  ),
                  SizedBox(height: 12),
                  if (_videoFile != null)
                    Text(
                      'Selected: ${_videoFile!.path.split('/').last}',
                      style: TextStyle(color: Colors.green),
                    )
                  else
                    Text('No video selected'),
                  SizedBox(height: 12),
                  ElevatedButton(
                    onPressed: _uploading ? null : _pickVideo,
                    child: Text('Pick Video'),
                  ),
                ],
              ),
            ),
          ),
          SizedBox(height: 16),

          // Video Progress
          if (_videoFile != null && _uploading)
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Video Upload Progress',
                      style: Theme.of(context).textTheme.titleSmall,
                    ),
                    SizedBox(height: 12),
                    GradientProgressBar(
                      progress: _videoProgress,
                      label: UploadService.formatBytes(
                        (_videoFile!.lengthSync() * _videoProgress).toInt(),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          SizedBox(height: 16),

          // Thumbnail Selection
          Card(
            child: Padding(
              padding: EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Select Thumbnail',
                    style: Theme.of(context).textTheme.titleMedium,
                  ),
                  SizedBox(height: 12),
                  if (_thumbnailFile != null)
                    Text(
                      'Selected: ${_thumbnailFile!.path.split('/').last}',
                      style: TextStyle(color: Colors.green),
                    )
                  else
                    Text('No thumbnail selected'),
                  SizedBox(height: 12),
                  ElevatedButton(
                    onPressed: _uploading ? null : _pickThumbnail,
                    child: Text('Pick Thumbnail'),
                  ),
                ],
              ),
            ),
          ),
          SizedBox(height: 16),

          // Thumbnail Progress
          if (_thumbnailFile != null && _uploading)
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Thumbnail Upload Progress',
                      style: Theme.of(context).textTheme.titleSmall,
                    ),
                    SizedBox(height: 12),
                    GradientProgressBar(
                      progress: _thumbnailProgress,
                      label: UploadService.formatBytes(
                        (_thumbnailFile!.lengthSync() * _thumbnailProgress).toInt(),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          SizedBox(height: 16),

          // Status Message
          if (_statusMessage.isNotEmpty)
            Card(
              color: Colors.blue[50],
              child: Padding(
                padding: EdgeInsets.all(12),
                child: Text(
                  _statusMessage,
                  style: TextStyle(color: Colors.blue[900]),
                ),
              ),
            ),
          SizedBox(height: 16),

          // Upload Button
          ElevatedButton.icon(
            onPressed: _uploading ? null : _uploadFiles,
            icon: _uploading ? SizedBox(
              height: 20,
              width: 20,
              child: CircularProgressIndicator(
                strokeWidth: 2,
                valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
              ),
            ) : Icon(Icons.cloud_upload),
            label: Text(_uploading ? 'Uploading...' : 'Upload Files'),
            style: ElevatedButton.styleFrom(
              padding: EdgeInsets.symmetric(vertical: 16),
            ),
          ),
        ],
      ),
    );
  }
}
```

---

## 5. Usage in Your App

### Example Screen

```dart
import 'package:flutter/material.dart';

class CreatePostScreen extends StatefulWidget {
  final String authToken;

  const CreatePostScreen({required this.authToken});

  @override
  State<CreatePostScreen> createState() => _CreatePostScreenState();
}

class _CreatePostScreenState extends State<CreatePostScreen> {
  String? _uploadedVideoUrl;
  String? _uploadedThumbnailUrl;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Create Post'),
      ),
      body: _uploadedVideoUrl == null
          ? VideoUploadWidget(
              authToken: widget.authToken,
              onUploadComplete: (videoUrl, thumbnailUrl) {
                setState(() {
                  _uploadedVideoUrl = videoUrl;
                  _uploadedThumbnailUrl = thumbnailUrl;
                });
              },
            )
          : Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.check_circle, color: Colors.green, size: 80),
                  SizedBox(height: 16),
                  Text('Files uploaded successfully!'),
                  SizedBox(height: 16),
                  ElevatedButton(
                    onPressed: () {
                      setState(() {
                        _uploadedVideoUrl = null;
                        _uploadedThumbnailUrl = null;
                      });
                    },
                    child: Text('Upload More'),
                  ),
                ],
              ),
            ),
    );
  }
}
```

---

## 6. Best Practices

### Performance Tips
```dart
// 1. Show progress during upload
onSendProgress: (sent, total) {
  setState(() {
    progress = sent / total;
  });
};

// 2. Cancel long uploads
CancelToken _cancelToken = CancelToken();
// To cancel: _cancelToken.cancel('Upload cancelled');

// 3. Handle errors gracefully
try {
  // upload code
} on DioException catch (e) {
  if (e.type == DioExceptionType.cancel) {
    print('Upload cancelled');
  }
}

// 4. Set timeouts
options: Options(
  sendTimeout: Duration(seconds: 300),
  receiveTimeout: Duration(seconds: 300),
)
```

### UI/UX Tips
```dart
// 1. Show file size during upload
Text('${formatBytes(sent)} / ${formatBytes(total)}')

// 2. Disable button while uploading
ElevatedButton(
  onPressed: _uploading ? null : _upload,
  child: Text(_uploading ? 'Uploading...' : 'Upload'),
)

// 3. Show clear status messages
_statusMessage = 'Uploading video...';

// 4. Handle offline scenarios
if (!await _isConnected()) {
  showErrorDialog('No internet connection');
}
```

---

## 7. Testing

### Test Upload Locally

```dart
// main.dart
void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: Scaffold(
        appBar: AppBar(title: Text('Upload Test')),
        body: VideoUploadWidget(
          authToken: 'your_test_token',
          onUploadComplete: (video, thumbnail) {
            print('Video: $video');
            print('Thumbnail: $thumbnail');
          },
        ),
      ),
    );
  }
}
```

### Mock Response for Testing

```dart
// For testing without actual upload
Future<Map<String, dynamic>> mockUploadVideo({
  required File videoFile,
  required String authToken,
  required Function(int sent, int total) onProgress,
}) async {
  final fileSize = videoFile.lengthSync();
  
  for (int i = 0; i <= 100; i += 10) {
    await Future.delayed(Duration(milliseconds: 500));
    onProgress(fileSize * (i / 100), fileSize);
  }
  
  return {
    'success': true,
    'data': {
      'video': 'https://example.com/video.mp4',
      'path': 'videos/test.mp4',
      'size': fileSize,
    },
  };
}
```

---

## 8. API Endpoints Reference

### Upload Video
```
POST /api/post/uploadVideo
Headers:
  - APIKEY: retry123
  - AUTHTOKEN: your_auth_token
  - Content-Type: multipart/form-data

Body:
  video: <binary file>

Response:
{
  "status": true,
  "data": {
    "video": "https://...",
    "path": "...",
    "size": 52428800,
    "uploaded": 52428800
  }
}
```

### Upload Thumbnail
```
POST /api/post/uploadThumbnail
Headers:
  - APIKEY: retry123
  - AUTHTOKEN: your_auth_token
  - Content-Type: multipart/form-data

Body:
  thumbnail: <binary file>

Response:
{
  "status": true,
  "data": {
    "thumbnail": "https://...",
    "path": "...",
    "size": 1048576,
    "uploaded": 1048576
  }
}
```

---

## 9. Troubleshooting

| Issue | Solution |
|-------|----------|
| Progress not updating | Check `onSendProgress` is called with sent/total |
| Upload timeout | Increase `sendTimeout` value (300 seconds recommended) |
| File not found | Verify file path and permissions |
| 401 Unauthorized | Check APIKEY and AUTHTOKEN headers |
| 400 Bad file | Validate file format (MP4/MOV for video, JPEG/PNG for image) |
| Memory issues | Chunk large files (for files > 1GB) |

---

This guide provides everything you need to implement upload progress bars in your Flutter Groovsta app!
