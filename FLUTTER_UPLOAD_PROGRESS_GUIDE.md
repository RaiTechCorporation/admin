# Flutter Upload Progress Tracking Guide

## Overview
The API now returns file size information (`size` and `uploaded` fields) to help track upload progress on the client side using Dio's `onSendProgress` callback.

## Implementation

### 1. Upload Service with Progress Tracking

```dart
import 'package:dio/dio.dart';

class UploadService {
  final Dio _dio = Dio();
  
  Future<void> uploadVideo({
    required File videoFile,
    required String authToken,
    required Function(int sent, int total) onProgress,
  }) async {
    try {
      FormData formData = FormData.fromMap({
        'video': await MultipartFile.fromFile(videoFile.path),
      });

      Response response = await _dio.post(
        'https://your-api.com/api/post/uploadVideo',
        data: formData,
        options: Options(
          headers: {
            'APIKEY': 'retry123',
            'AUTHTOKEN': authToken,
          },
        ),
        onSendProgress: (int sent, int total) {
          // Calculate progress percentage
          double progress = (sent / total) * 100;
          print('Upload Progress: ${progress.toStringAsFixed(2)}%');
          
          // Call callback for UI update
          onProgress(sent, total);
        },
      );

      if (response.statusCode == 200) {
        final data = response.data['data'];
        print('Video uploaded: ${data['video']}');
        print('File size: ${data['size']} bytes');
      }
    } catch (e) {
      print('Upload error: $e');
      rethrow;
    }
  }

  Future<void> uploadThumbnail({
    required File imageFile,
    required String authToken,
    required Function(int sent, int total) onProgress,
  }) async {
    try {
      FormData formData = FormData.fromMap({
        'thumbnail': await MultipartFile.fromFile(imageFile.path),
      });

      Response response = await _dio.post(
        'https://your-api.com/api/post/uploadThumbnail',
        data: formData,
        options: Options(
          headers: {
            'APIKEY': 'retry123',
            'AUTHTOKEN': authToken,
          },
        ),
        onSendProgress: (int sent, int total) {
          double progress = (sent / total) * 100;
          print('Thumbnail Upload Progress: ${progress.toStringAsFixed(2)}%');
          onProgress(sent, total);
        },
      );

      if (response.statusCode == 200) {
        final data = response.data['data'];
        print('Thumbnail uploaded: ${data['thumbnail']}');
      }
    } catch (e) {
      print('Upload error: $e');
      rethrow;
    }
  }
}
```

### 2. Progress Bar Widget

```dart
import 'package:flutter/material.dart';

class UploadProgressWidget extends StatefulWidget {
  final String fileName;
  final VoidCallback onUpload;

  const UploadProgressWidget({
    required this.fileName,
    required this.onUpload,
  });

  @override
  State<UploadProgressWidget> createState() => _UploadProgressWidgetState();
}

class _UploadProgressWidgetState extends State<UploadProgressWidget> {
  double _progress = 0.0;
  bool _uploading = false;
  String _progressText = '0%';

  void _handleProgress(int sent, int total) {
    setState(() {
      _progress = sent / total;
      _progressText = '${(_progress * 100).toStringAsFixed(1)}%';
    });
  }

  Future<void> _startUpload() async {
    setState(() {
      _uploading = true;
      _progress = 0.0;
    });

    try {
      final uploadService = UploadService();
      
      // Example: Upload video
      // await uploadService.uploadVideo(
      //   videoFile: videoFile,
      //   authToken: authToken,
      //   onProgress: _handleProgress,
      // );

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Upload completed!')),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Upload failed: $e')),
      );
    } finally {
      setState(() {
        _uploading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text(widget.fileName),
        SizedBox(height: 8),
        LinearProgressIndicator(
          value: _progress,
          minHeight: 6,
          backgroundColor: Colors.grey[300],
          valueColor: AlwaysStoppedAnimation<Color>(Colors.blue),
        ),
        SizedBox(height: 8),
        Text(_progressText),
        SizedBox(height: 16),
        ElevatedButton(
          onPressed: _uploading ? null : _startUpload,
          child: Text(_uploading ? 'Uploading...' : 'Upload'),
        ),
      ],
    );
  }
}
```

### 3. Usage Example

```dart
class VideoUploadScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Upload Video')),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: UploadProgressWidget(
          fileName: 'my_video.mp4',
          onUpload: () {},
        ),
      ),
    );
  }
}
```

## API Response Example

### Success Response
```json
{
  "status": true,
  "message": "Video uploaded successfully",
  "data": {
    "video": "https://groovsta-bucket.s3.ap-south-1.amazonaws.com/videos/1771832526066_video.mp4",
    "path": "Groovsta/videos/1771832526066_video.mp4",
    "size": 52428800,
    "uploaded": 52428800
  }
}
```

## Progress Calculation Formula

```
progress_percent = (bytes_sent / total_bytes) * 100
```

Where:
- **bytes_sent**: Bytes transferred in current request
- **total_bytes**: Total file size

## Notes

1. **onSendProgress** fires as data is being sent to the server
2. The server receives the complete file after progress reaches 100%
3. Larger files show more granular progress updates
4. Network speed affects progress update frequency
5. `size` field in response validates the total upload size

## Testing

Test with curl to verify upload:
```bash
curl -X POST http://localhost:8000/api/post/uploadVideo \
  -H "APIKEY: retry123" \
  -H "AUTHTOKEN: your_token" \
  -F "video=@/path/to/video.mp4"
```
