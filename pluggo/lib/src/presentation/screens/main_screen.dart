
import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config/colors.dart';
import '../../config/routes/app_routes.dart';
import '../../config/routes/app_routes.gr.dart';
import '../../config/theme.dart';
import '../../provider/user_provider.dart';
import '../layout/layout_main.dart';
import '../provider/main_provider.dart';
import '../provider/theme_provider.dart';
import '../styles/spacings.dart';
import '../styles/typography.dart';
import '../widgets/appbar/header_mainpage.dart';

@RoutePage()
class MainScreen extends StatelessWidget {
  const MainScreen({super.key});

  @override
  Widget build(BuildContext context) {
    UserProvider userProvider = Provider.of<UserProvider>(context, listen: true);
    return ThemeProvider(
        theme: defaultTheme,
        child: Consumer<MainProvider>(
            builder: (context, provider, child) {
              return LayoutMain(
                    appBar: const HeaderMainpage(),
                    warning: false,
                    isBackgroundDark: false,
                    showFooterMain: true,
                    isLoadingPage: provider.isLoading,
                    isAutomaticAntecipation: false,
                    child: Column(
                      children: [
                        SizedBox(height: HeightSpacing.medium),

                        Padding(
                          padding: EdgeInsets.symmetric(horizontal: WidthSpacing.medium),
                          child: Column(

                            children: [

                              InkWell(
                                onTap: () async => {
                                  appRouter.push(const MapsRouter())
                                },
                                child: Container(
                                  padding: EdgeInsets.symmetric( horizontal: 30),
                                  width: WidthSpacing.baseWidthResolution,
                                  height: HeightSpacing.custom(90),
                                  decoration: BoxDecoration(
                                    color: appColors.light,
                                    borderRadius: BorderRadius.circular(15),
                                  ),
                                  child: Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text(
                                          "Abrir mapa",
                                          style: AppTypography.infoItemBrandsWhite(context)
                                      ),
                                      Center(
                                        child: Icon(
                                          Icons.search,
                                          size: HeightSpacing.custom(10) + 25,
                                          color: appColors.white, // ou qualquer cor desejada
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  );
            }
        )
    );
  }
}
