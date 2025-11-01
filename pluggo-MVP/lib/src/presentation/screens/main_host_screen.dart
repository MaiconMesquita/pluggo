
import 'package:auto_route/auto_route.dart';
import 'package:flutter/material.dart';
import 'package:pluggo/src/config/routes/app_routes.dart';
import 'package:pluggo/src/config/routes/app_routes.gr.dart';
import 'package:provider/provider.dart';

import '../../config/colors.dart';
import '../../config/theme.dart';
import '../../provider/user_provider.dart';
import '../layout/layout_main.dart';
import '../provider/main_provider.dart';
import '../provider/theme_provider.dart';
import '../styles/spacings.dart';
import '../styles/typography.dart';
import '../widgets/appbar/header_mainpage.dart';
import '../widgets/product_list/product_list_style.dart';

@RoutePage()
class MainHostScreen extends StatelessWidget {
  const MainHostScreen({super.key});

  @override
  Widget build(BuildContext context) {
    UserProvider userProvider = Provider.of<UserProvider>(context, listen: true);
    return ThemeProvider(
        theme: defaultTheme,
        child: Consumer<MainProvider>(
            builder: (context, provider, child) {
              return RefreshIndicator(
                  color: ThemeProvider
                      .of(context)
                      .appColors
                      .primary,
                  backgroundColor: ThemeProvider
                      .of(context)
                      .themeColors
                      .background,
                  onRefresh: provider.refresh,
                  child: LayoutMain(
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
                                  //await provider.globalList(),
                                  //appRouter.push(GlobalProductRoute())
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
                                          "Adicionar carregador",
                                          style: AppTypography.infoItemBrandsWhite(context)
                                      ),
                                      Center(
                                        child: Icon(
                                          Icons.add,
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
                  )
              );
            }
        )
    );
  }
}
