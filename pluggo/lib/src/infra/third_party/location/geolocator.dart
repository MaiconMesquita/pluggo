
import 'dart:io' show Platform;

import 'package:geolocator/geolocator.dart';

import '../../../../utils/warning_messages.dart';
import '../../../domain/entity/geo_position.dart';
import '../../../domain/exceptions/no_popup_exception.dart';
import '../../../domain/exceptions/permission_exception.dart';
import 'location.dart';

class GeolocatorAdapter implements LocationService {
  @override
  Future<GeoPosition> determinePosition() async {
    final serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      throw PermissionException(AlertMessages.locationServicesDisabled);
    }

    LocationPermission permission = await Geolocator.checkPermission();

    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        throw PermissionException(AlertMessages.locationPermissionsDenied);
      }
    }

    if (permission == LocationPermission.deniedForever) {
      final confirmed = await ShowPopUp.showConfirmationPopUp(
        AlertMessages.locationPermissionsPermanentlyDenied,
      );
      if (confirmed == true) await Geolocator.openAppSettings();
      throw NoPopUpException(AlertMessages.locationPermissionsDenied);
    }

    // Define settings dependendo da plataforma
    final settings = LocationSettings(
      accuracy: LocationAccuracy.medium,
      distanceFilter: 10, // metros mínimos para atualizar a posição
    );

    final position = await Geolocator.getCurrentPosition(
      locationSettings: settings,
    ).timeout(
      const Duration(seconds: 5),
      onTimeout: () async {
        final lastPos = await Geolocator.getLastKnownPosition();
        if (lastPos != null) return lastPos;
        return Position(
          latitude: 0,
          longitude: 0,
          timestamp: DateTime.now(),
          accuracy: 0,
          altitude: 0,
          heading: 0,
          speed: 0,
          speedAccuracy: 0,
          altitudeAccuracy: 0,
          headingAccuracy: 0,
        );
      },
    );

    return GeoPosition(position.latitude, position.longitude);
  }
}
