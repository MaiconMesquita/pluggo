
import '../../../domain/entity/geo_position.dart';

abstract class LocationService {
  Future<GeoPosition> determinePosition();
}