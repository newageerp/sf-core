import React from "react";
import ViewContent from "../view/ViewContent";
import EditContent from "../edit/EditContent";


import ToolbarActionButton from "../toolbar/ToolbarActionButton";
import EditContentPopup from "../edit/EditContentPopup";

import FormFieldLabel from "../form/FormFieldLabel";

import { Wide, Compact } from '@newageerp/v3.bundles.form-bundle';
import WideRow from "../form/rows/WideRow";
import CompactRow from "../form/rows/CompactRow";

import EditableForm from "../form/EditableForm";
import FormFieldSeparator from "../form/FormFieldSeparator";
import FormLabel from "../form/FormLabel";
import RoForm from "../form/RoForm";

import { FreeBgBadgeWidget } from "@newageerp/v3.templates.widgets.free-bg-badge-widget";
import FormHint from "../form/FormHint";
import { CustomEditComponentsMap } from "../../_custom/edit/CustomEditComponentsMap";
import { CustomViewComponentsMap } from "../../_custom/view/CustomViewComponentsMap";
import EditFormContent from "../edit/EditFormContent";
import ViewFormContent from "../view/ViewFormContent";
import RequestRecordProvider from "../db/RequestRecordProvider";
import RequestRecordProviderInner from "../db/RequestRecordProviderInner";

import PrimitiveString from "../primitives/PrimitiveString";


import ViewPdfWidget, { ViewPdfItem } from "../view/Widget/ViewPdfWidget";
import { PluginsMap } from "../../../Plugins/PluginsMap";
import ArrayRoColumn from "../list/columns/ArrayRoColumn";
import AudioRoColumn from "../list/columns/AudioRoColumn";
import BoolRoColumn from "../list/columns/BoolRoColumn";
import ColorRoColumn from "../list/columns/ColorRoColumn";
import DateRoColumn from "../list/columns/DateRoColumn";
import DateTimeRoColumn from "../list/columns/DateTimeRoColumn";
import EnumMultiNumberRoColumn from "../list/columns/EnumMultiNumberRoColumn";
import EnumMultiTextRoColumn from "../list/columns/EnumMultiTextRoColumn";
import EnumNumberRoColumn from "../list/columns/EnumNumberRoColumn";
import EnumTextRoColumn from "../list/columns/EnumTextRoColumn";
import FileMultipleRoColumn from "../list/columns/FileMultipleRoColumn";
import FileRoColumn from "../list/columns/FileRoColumn";
import FloatRoColumn from "../list/columns/FloatRoColumn";
import ImageRoColumn from "../list/columns/ImageRoColumn";
import LargeTextRoColumn from "../list/columns/LargeTextRoColumn";
import NumberRoColumn from "../list/columns/NumberRoColumn";
import ObjectRoColumn from "../list/columns/ObjectRoColumn";
import StatusRoColumn from "../list/columns/StatusRoColumn";
import StringArrayRoColumn from "../list/columns/StringArrayRoColumn";
import StringRoColumn from "../list/columns/StringRoColumn";
import ListDataSource from "../list/ListDataSource";
import ViewStatusWidgetWithActions from "../view/Widget/ViewStatusWidgetWithActions";
import { CustomListComponentsMap } from "../../_custom/tabs/CustomListComponentsMap";
import NumberCardDfWidget from "../widgets/NumberCardDfWidget";

import LargeTextEditableColumn from "../list/editable-columns/LargeTextEditableColumn";
import BoolEditableColumn from "../list/editable-columns/BoolEditableColumn"
import FormFieldTagCloudTemplate from "../form/FormFieldTagCloudTemplate";
import WhiteCardWithViewFormWidget from "../widgets/WhiteCardWithViewFormWidget";


import AddButton from "../form/editable-fields/components/ArrayEditableField/AddButton";

import FormError from "../form/FormError";
import HtmlEditorEditableField from "../form/editable-fields/HtmlEditorEditableField";
import AppInner from "../app/AppInner";

import * as ButtonsBundle from '@newageerp/v3.bundles.buttons-bundle';
import * as ModalBundle from '@newageerp/v3.bundles.modal-bundle';
import * as LayoutBundle from '@newageerp/v3.bundles.layout-bundle';
import * as PopupBundle from '@newageerp/v3.bundles.popup-bundle';
import * as AppBundle from '@newageerp/v3.bundles.app-bundle';
import * as MenuBundle from "@newageerp/v3.bundles.menu-bundle";
import * as WidgetsBundle from "@newageerp/v3.bundles.widgets-bundle";

import * as DataBundle from "@newageerp/v3.bundles.data-bundle";

import OneToOneWidget from "../widgets/OneToOneWidget";
import DivContainer from "../layout/DivContainer";
import EditContentInline from "../edit/EditContentInline";

export const componentsMap: any = {
  "App": AppInner,
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

  "view.content": ViewContent,
  "view.formcontent": ViewFormContent,

  "edit.content": EditContent,
  "edit.formcontent": EditFormContent,

  "view.statuswidgetwithactions": ViewStatusWidgetWithActions,

  "widgets.WhiteCardWithViewFormWidget": WhiteCardWithViewFormWidget,

  "widgets.dfnumberwidget": NumberCardDfWidget,

  "toolbar.action-button": ToolbarActionButton,
  "edit.contentpopup": EditContentPopup,

  "edit.contentinline": EditContentInline,

  "widgets.freebgbadgewidget": FreeBgBadgeWidget,

  "form.rows.widerow": WideRow,
  "form.rows.compactrow": CompactRow,
  "form.FormError": FormError,

  "form.editableform": EditableForm,
  "form.fieldlabel": FormFieldLabel,
  "form.fieldseparator": FormFieldSeparator,
  "form.fieldtagcloud": FormFieldTagCloudTemplate,
  "form.label": FormLabel,
  "form.hint": FormHint,
  "form.roform": RoForm,


  "list.ro.arraycolumn": ArrayRoColumn,
  "list.ro.audiocolumn": AudioRoColumn,
  "list.ro.boolcolumn": BoolRoColumn,
  "list.ro.colorcolumn": ColorRoColumn,
  "list.ro.datecolumn": DateRoColumn,
  "list.ro.datetimecolumn": DateTimeRoColumn,
  "list.ro.enummultinumbercolumn": EnumMultiNumberRoColumn,
  "list.ro.enummultitextcolumn": EnumMultiTextRoColumn,
  "list.ro.enumnumbercolumn": EnumNumberRoColumn,
  "list.ro.enumtextcolumn": EnumTextRoColumn,
  "list.ro.filemultiplecolumn": FileMultipleRoColumn,
  "list.ro.filecolumn": FileRoColumn,
  "list.ro.floatcolumn": FloatRoColumn,
  "list.ro.imagecolumn": ImageRoColumn,
  "list.ro.largetextcolumn": LargeTextRoColumn,
  "list.ro.numbercolumn": NumberRoColumn,
  "list.ro.objectcolumn": ObjectRoColumn,
  "list.ro.statuscolumn": StatusRoColumn,
  "list.ro.stringarraycolumn": StringArrayRoColumn,
  "list.ro.stringcolumn": StringRoColumn,

  "list.editable.largetextcolumn": LargeTextEditableColumn,
  "list.editable.boolcolumn": BoolEditableColumn,

  "db.request.recordprovider": RequestRecordProvider,
  "db.request.recordprovider.inner": RequestRecordProviderInner,

  "view.pdf.container": ViewPdfWidget,
  "view.pdf.item": ViewPdfItem,

  "buttons.toolbar-button-with-menu": ButtonsBundle.ToolbarButtonWithMenu,
  'buttons.toolbar-button-element-with-action': ButtonsBundle.ToolbarButtonElementWithAction,
  'buttons.toolbar-button-list-with-action': ButtonsBundle.ToolbarButtonListWithAction,

  'list.list-data-source': ListDataSource,

  'primitives.string': PrimitiveString,

  'widgets.oneToOneWidget': OneToOneWidget,
  'layout.div': DivContainer,

  'form.block.compact': Compact,
  'form.block.wide': Wide,

  ...CustomEditComponentsMap,
  ...CustomViewComponentsMap,
  ...CustomListComponentsMap,
  ...PluginsMap,
};
