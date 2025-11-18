import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:pluggo/src/domain/entity/device.dart';
import 'package:pluggo/src/presentation/styles/spacings.dart';
import 'package:provider/provider.dart';

import '../../config/theme.dart';
import '../../domain/entity/spot.dart';
import '../layout/layout_main.dart';
import '../provider/maps_provider.dart';
import '../provider/theme_provider.dart';
import '../widgets/appbar/header_mainpage.dart';

@RoutePage()
class MapsScreen extends StatefulWidget {
  @override
  _MapsScreenState createState() => _MapsScreenState();
}

class _MapsScreenState extends State<MapsScreen> {
  GoogleMapController? _controller;

  LatLng get _center => LatLng(Device.latitude, Device.longitude);

  Set<Marker> _createMarkers(List<Spot> spots) {
    final markers = <Marker>{};

    // Marker da localização atual (Device)
    markers.add(
      Marker(
        markerId: const MarkerId("user_location"),
        position: LatLng(Device.latitude, Device.longitude),
        icon: BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueRed),
        infoWindow: const InfoWindow(title: "Você está aqui"),
      ),
    );

    // Markers dos spots
    markers.addAll(
      spots.map((s) {
        return Marker(
          markerId: MarkerId("spot_${s.id}"),
          position: LatLng(s.latitude, s.longitude),
          infoWindow: InfoWindow(title: s.name ?? "Carregador"),
          icon: BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueBlue),
        );
      }),
    );

    return markers;
  }



  @override
  void initState() {
    super.initState();

    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (Device.latitude != 0 && Device.longitude != 0) {
        _controller?.animateCamera(
          CameraUpdate.newLatLng(_center),
        );
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return ThemeProvider(
      theme: defaultTheme,
      child: Consumer<MapsProvider>(
        builder: (context, provider, child) {
          print("SPOTS RECEBIDOS:");
          for (var s in provider.spots) {
            print("Spot: ${s.name} - lat: ${s.latitude}, lng: ${s.longitude}");
          }

          return LayoutMain(
            isFullScreen: true,
            appBar: const HeaderMainpage(),
            warning: false,
            isBackgroundDark: false,
            showFooterMain: true,
            isLoadingPage: provider.isLoading,
            isAutomaticAntecipation: false,
            child: GoogleMap(
                onMapCreated: (controller) => _controller = controller,
                initialCameraPosition: CameraPosition(
                  target: _center,
                  zoom: 14,
                ),
              myLocationEnabled: true,
              myLocationButtonEnabled: true,
                markers: _createMarkers(provider.spots),
              ),
          );
        },
      ),
    );
  }
}
