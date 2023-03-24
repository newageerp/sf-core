import React from "react";

import { CustomEditComponentsMap } from "../../_custom/edit/CustomEditComponentsMap";
import { CustomViewComponentsMap } from "../../_custom/view/CustomViewComponentsMap";
import { CustomListComponentsMap } from "../../_custom/tabs/CustomListComponentsMap";

import { PluginsMap } from "../../../Plugins/PluginsMap";

import ListDataSource from "../list/ListDataSource";

import AppInner from "../app/AppInner";

import * as ButtonsBundle from '@newageerp/v3.bundles.buttons-bundle';
import * as ModalBundle from '@newageerp/v3.bundles.modal-bundle';
import * as LayoutBundle from '@newageerp/v3.bundles.layout-bundle';
import * as PopupBundle from '@newageerp/v3.bundles.popup-bundle';
import * as AppBundle from '@newageerp/v3.bundles.app-bundle';
import * as MenuBundle from "@newageerp/v3.bundles.menu-bundle";
import * as WidgetsBundle from "@newageerp/v3.bundles.widgets-bundle";

import * as DataBundle from "@newageerp/v3.bundles.data-bundle";
import * as AuthBundle from "@newageerp/v3.bundles.auth-bundle";
import * as FormBundle from "@newageerp/v3.bundles.form-bundle";

import * as TypographyBundle from "@newageerp/v3.bundles.typography-bundle";

import { RecordProvider } from "@newageerp/v3.app.mvc.record-provider";

export const componentsMap: any = {
  "App": AppInner,
  "TypographyBundle": (comp: string) => {
    // @ts-ignore
    return TypographyBundle[comp];
  },
  "ButtonsBundle": (comp: string) => {
    // @ts-ignore
    return ButtonsBundle[comp];
  },
  "ModalBundle": (comp: string) => {
    // @ts-ignore
    return ModalBundle[comp];
  },
  "LayoutBundle": (comp: string) => {
    // @ts-ignore
    return LayoutBundle[comp];
  },
  "PopupBundle": (comp: string) => {
    // @ts-ignore
    return PopupBundle[comp];
  },
  "AppBundle": (comp: string) => {
    // @ts-ignore
    return AppBundle[comp];
  },
  "MenuBundle": (comp: string) => {
    // @ts-ignore
    return MenuBundle[comp];
  },
  "WidgetsBundle": (comp: string) => {
    if (!(comp in WidgetsBundle)) {
      console.log(`${comp} not found in WidgetsBundle`);
    }
    // @ts-ignore
    return WidgetsBundle[comp];
  },
  "DataBundle": (comp: string) => {
    if (!(comp in DataBundle)) {
      console.log(`${comp} not found in DataBundle`);
    }
    // @ts-ignore
    return DataBundle[comp];
  },
  "FormBundle": (comp: string) => {
    // @ts-ignore
    return FormBundle[comp];
  },
  "AuthBundle": (comp: string) => {
    // @ts-ignore
    return AuthBundle[comp];
  },


  "db.request.recordprovider": RecordProvider,

  'list.list-data-source': ListDataSource,


  ...CustomEditComponentsMap,
  ...CustomViewComponentsMap,
  ...CustomListComponentsMap,
  ...PluginsMap,
};
