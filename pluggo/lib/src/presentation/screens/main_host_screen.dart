
import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:pluggo/src/config/routes/app_routes.gr.dart';
import 'package:pluggo/src/presentation/styles/spacings.dart';
import 'package:provider/provider.dart';

import '../../config/theme.dart';
import '../../provider/user_provider.dart';
import '../layout/layout_default1.dart';
import '../layout/layout_main.dart';
import '../provider/main_provider.dart';
import '../provider/theme_provider.dart';
import '../widgets/appbar/header_mainpage.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

@RoutePage()
class MainHostScreen extends StatefulWidget {
  const MainHostScreen({super.key});

  @override
  _MainHostScreenState createState() => _MainHostScreenState();
}

class _MainHostScreenState extends State<MainHostScreen> {
  late GoogleMapController mapController;

  // Ponto inicial do mapa (pode ser a localização do host)
  final LatLng initialPosition = const LatLng(-23.512938, -47.455912);

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final provider = Provider.of<MainProvider>(context, listen: false);
      provider.loadMySpots(); // carrega spots do backend
    });
  }

  Set<Marker> buildMarkers(List spots) {
    return spots.map((spot) {
      return Marker(
        markerId: MarkerId(spot.id.toString()),
        position: LatLng(spot.latitude, spot.longitude),
        infoWindow: InfoWindow(
          title: spot.name,
          snippet: 'Clique para detalhes',
        ),
        onTap: () {
          // Aqui você pode abrir o detalhe do spot ou editar
          print('Marker clicado: ${spot.name}');
        },
      );
    }).toSet();
  }

  @override
  Widget build(BuildContext context) {

    return ThemeProvider(
      theme: defaultTheme,
      child: Consumer<MainProvider>(
        builder: (context, provider, child) {
          return  DefaultLayout1(
                appBar: HeaderMainpage(
                  color: ThemeProvider.of(context).appColors.white,
                ),
                isBackgroundDark: true,
                isLoadingPage: provider.isLoading,
                child:
            Column(
              children: [
                // botão
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: InkWell(
                    onTap: () => appRouter.push(CreateSpotRoute()),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 30),
                      height: 90,
                      decoration: BoxDecoration(
                        color: Theme.of(context).colorScheme.secondary,
                        borderRadius: BorderRadius.circular(15),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: const [
                          Text("Adicionar carregador", style: TextStyle(color: Colors.white, fontSize: 18)),
                          Icon(Icons.add, color: Colors.white, size: 30),
                        ],
                      ),
                    ),
                  ),
                ),
                // mapa
                SizedBox(
                  height: HeightSpacing.custom(600), // ou outro valor fixo
                  child: GoogleMap(
                    initialCameraPosition: CameraPosition(target: initialPosition, zoom: 5),
                    markers: buildMarkers(provider.mySpots),
                    onMapCreated: (controller) => mapController = controller,
                    myLocationEnabled: true,
                    myLocationButtonEnabled: true,
                    zoomControlsEnabled: true,
                  ),
                ),
                Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: InkWell(
                    onTap: provider.loadMySpots,
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 30),
                      height: 90,
                      decoration: BoxDecoration(
                        color: Theme.of(context).colorScheme.secondary,
                        borderRadius: BorderRadius.circular(15),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: const [
                          Text("Atualizar", style: TextStyle(color: Colors.white, fontSize: 18)),
                          Icon(Icons.add, color: Colors.white, size: 30),
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
