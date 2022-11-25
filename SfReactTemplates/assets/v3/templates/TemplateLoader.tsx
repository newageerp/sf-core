import React, { Fragment, useContext, useState, useEffect } from "react";
import axios from "axios";
import ViewContent from "../view/ViewContent";
import PopupWindow from "../popup/PopupWindow";
import ToolbarTitle from "../maintoolbar/ToolbarTitle";
import ListContent from "../list/ListContent";
import TableTr from "../table/TableTr";
import Table from "../table/Table";
import DataString from "../data/DataString";
import TableTh from "../table/TableTh";
import EditContent from "../edit/EditContent";
import ViewFilesWidget from "../view/Widget/ViewFilesWidget";

import FlexRow from "../layout/FlexRow";
import { NumberCardWidget } from "@newageerp/v3.bundles.widgets-bundle";
import ViewStatusWidget from "../view/Widget/ViewStatusWidget";
import ToolbarActionButton from "../toolbar/ToolbarActionButton";
import EditContentPopup from "../edit/EditContentPopup";

import FormFieldLabel from "../form/FormFieldLabel";
import StringEditableField from "../form/editable-fields/StringEditableField";
import ArrayEditableField from "../form/editable-fields/ArrayEditableField";
import AudioEditableField from "../form/editable-fields/AudioEditableField";
import BoolEditableField from "../form/editable-fields/BoolEditableField";
import ColorEditableField from "../form/editable-fields/ColorEditableField";
import DateEditableField from "../form/editable-fields/DateEditableField";
import DateTimeEditableField from "../form/editable-fields/DateTimeEditableField";
import EnumMultiNumberEditableField from "../form/editable-fields/EnumMultiNumberEditableField";
import EnumMultiTextEditableField from "../form/editable-fields/EnumMultiTextEditableField";
import EnumNumberEditableField from "../form/editable-fields/EnumNumberEditableField";
import EnumTextEditableField from "../form/editable-fields/EnumTextEditableField";
import FileEditableField from "../form/editable-fields/FileEditableField";
import FileMultipleEditableField from "../form/editable-fields/FileMultipleEditableField";
import FloatEditableField from "../form/editable-fields/FloatEditableField";
import ImageEditableField from "../form/editable-fields/ImageEditableField";
import LargeTextEditableField from "../form/editable-fields/LargeTextEditableField";
import NumberEditableField from "../form/editable-fields/NumberEditableField";
import ObjectEditableField from "../form/editable-fields/ObjectEditableField";
import StatusEditableField from "../form/editable-fields/StatusEditableField";
import StringArrayEditableField from "../form/editable-fields/StringArrayEditableField";

import WideRow from "../form/rows/WideRow";
import CompactRow from "../form/rows/CompactRow";
import ArrayRoField from "../form/ro-fields/ArrayRoField";
import AudioRoField from "../form/ro-fields/AudioRoField";
import BoolRoField from "../form/ro-fields/BoolRoField";
import ColorRoField from "../form/ro-fields/ColorRoField";
import DateRoField from "../form/ro-fields/DateRoField";
import DateTimeRoField from "../form/ro-fields/DateTimeRoField";
import EnumMultiNumberRoField from "../form/ro-fields/EnumMultiNumberRoField";
import EnumMultiTextRoField from "../form/ro-fields/EnumMultiTextRoField";
import EnumNumberRoField from "../form/ro-fields/EnumNumberRoField";
import EnumTextRoField from "../form/ro-fields/EnumTextRoField";
import FileMultipleRoField from "../form/ro-fields/FileMultipleRoField";
import FileRoField from "../form/ro-fields/FileRoField";
import FloatRoField from "../form/ro-fields/FloatRoField";
import ImageRoField from "../form/ro-fields/ImageRoField";
import LargeTextRoField from "../form/ro-fields/LargeTextRoField";
import NumberRoField from "../form/ro-fields/NumberRoField";
import ObjectRoField from "../form/ro-fields/ObjectRoField";
import StatusRoField from "../form/ro-fields/StatusRoField";
import StringArrayRoField from "../form/ro-fields/StringArrayRoField";
import StringRoField from "../form/ro-fields/StringRoField";
import EditableForm from "../form/EditableForm";
import FormFieldSeparator from "../form/FormFieldSeparator";
import FormFieldTagCloud from "../form/FormFieldTagCloud";
import FormLabel from "../form/FormLabel";
import RoForm from "../form/RoForm";

import { FreeBgBadgeWidget } from "@newageerp/v3.templates.widgets.free-bg-badge-widget";
import FormHint from "../form/FormHint";
import { CustomEditComponentsMap } from "../../_custom/edit/CustomEditComponentsMap";
import EditFormContent from "../edit/EditFormContent";
import ViewFormContent from "../view/ViewFormContent";
import RequestRecordProvider from "../db/RequestRecordProvider";
import RequestRecordProviderInner from "../db/RequestRecordProviderInner";
import ArrayDfRoField from "../form/df-ro-fields/ArrayDfRoField";
import AudioDfRoField from "../form/df-ro-fields/AudioDfRoField";
import BoolDfRoField from "../form/df-ro-fields/BoolDfRoField";
import ColorDfRoField from "../form/df-ro-fields/ColorDfRoField";
import DateDfRoField from "../form/df-ro-fields/DateDfRoField";
import DateTimeDfRoField from "../form/df-ro-fields/DateTimeDfRoField";
import EnumMultiNumberDfRoField from "../form/df-ro-fields/EnumMultiNumberDfRoField";
import EnumMultiTextDfRoField from "../form/df-ro-fields/EnumMultiTextDfRoField";
import EnumNumberDfRoField from "../form/df-ro-fields/EnumNumberDfRoField";
import EnumTextDfRoField from "../form/df-ro-fields/EnumTextDfRoField";
import FileMultipleDfRoField from "../form/df-ro-fields/FileMultipleDfRoField";
import FileDfRoField from "../form/df-ro-fields/FileDfRoField";
import FloatDfRoField from "../form/df-ro-fields/FloatDfRoField";
import ImageDfRoField from "../form/df-ro-fields/ImageDfRoField";
import LargeTextDfRoField from "../form/df-ro-fields/LargeTextDfRoField";
import NumberDfRoField from "../form/df-ro-fields/NumberDfRoField";
import ObjectDfRoField from "../form/df-ro-fields/ObjectDfRoField";
import StatusDfRoField from "../form/df-ro-fields/StatusDfRoField";
import StringArrayDfRoField from "../form/df-ro-fields/StringArrayDfRoField";
import StringDfRoField from "../form/df-ro-fields/StringDfRoField";

import PrimitiveString from "../primitives/PrimitiveString";


import ViewPdfWidget, { ViewPdfItem } from "../view/Widget/ViewPdfWidget";
import WhiteCard from "../cards/WhiteCard";
import MainButton from "../buttons/MainButton";
import { PluginsMap } from "../../../Plugins/PluginsMap";
import ToolbarButtonWithMenu from "../buttons/ToolbarButtonWithMenu";
import MenuItemWithCreate from "../menu/MenuItemWithCreate";
import TableTd from "../table/TableTd";
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
import RsButton from "../buttons/RsButton";
import RsButtonTemplate from "../buttons/RsButtonTemplate";
import ToolbarButtonElementWithAction from "../buttons/ToolbarButtonElementWithAction"
import ListDataSource from "../list/ListDataSource";
import ListDataTable from "../list/ListDataTable";
import ViewStatusWidgetWithActions from "../view/Widget/ViewStatusWidgetWithActions";
import { CustomListComponentsMap } from "../../_custom/tabs/CustomListComponentsMap";
import NumberCardDfWidget from "../widgets/NumberCardDfWidget";
import ListToolbar from "../toolbar/ListToolbar";
import ListToolbarNewButton from "../toolbar/ListToolbarNewButton";
import ListToolbarQs from "../toolbar/ListToolbarQs";
import ListToolbarSort from "../toolbar/ListToolbarSort";
import { ListToolbarExport } from "../toolbar/ListToolbarExport";
import ListToolbarTabsSwitch from "../toolbar/ListToolbarTabsSwitch";
import ListToolbarDetailedSearch from "../toolbar/ListToolbarDetailedSearch";
import AddSelectButton from "../list/actions/AddSelectButton";
import MenuItemWithEdit from "../menu/MenuItemWithEdit";
import ListToolbarQuickFilters from "../toolbar/ListToolbarQuickFilters";
import LargeTextEditableColumn from "../list/editable-columns/LargeTextRoColumn";
import FormFieldTagCloudTemplate from "../form/FormFieldTagCloudTemplate";

import WhiteCardWithViewFormWidget from "../widgets/WhiteCardWithViewFormWidget";
import TabContainer from "../tabs/TabContainer";
import MailsContent from "../content-widgets/MailsContent";
import NotesContent from "../content-widgets/NotesContent";
import ListDataSummary from "../list/ListDataSummary";
import ListDataTotals from "../list/ListDataTotals";

import { MenuFolder, MenuTitle, MenuItem } from "@newageerp/v3.bundles.menu-bundle";
import AddButton from "../form/editable-fields/components/ArrayEditableField/AddButton";
import ToolbarButtonListWithAction from "../buttons/ToolbarButtonListWithAction";
import ElementBookmarkButton from "../element/ElementBookmarkButton";

export interface Template {
  comp: string;
  action: any;
  props: any;
}

export interface TemplateLoaderProviderValue {
  data: any;
}

export const TemplateLoaderContext =
  React.createContext<TemplateLoaderProviderValue>({
    data: {},
  });

export const useTemplateLoader = () => useContext(TemplateLoaderContext);

interface Props {
  templateName?: string;
  data?: any;

  templateData?: any;

  templates?: Template[];
}

export const getTemplateLoaderData = (templateName: string, data: any) => {

  const url = `/app/nae-core/react-templates/get/${templateName}`;
  return axios
    .post(
      url,
      { data: data },
      {
        headers: {
          // @ts-ignore
          Authorization: window.localStorage.getItem("token"),
          "Content-Type": "application/json",
        },
      }
    );

}

export default function TemplateLoader(props: Props) {
  const [isLoaded, setIsLoaded] = useState(
    !!props.templates && props.templates.length > 0 ? true : false
  );

  const [templates, setTemplates] = useState<Template[]>(
    !!props.templates ? props.templates : []
  );
  const [templatesData, setTemplatesData] = useState(
    props.templateData ? props.templateData : {}
  );
  const [remoteTemplatesData, setRemoteTemplatesData] = useState({});

  const [allTemplatesData, setAllTemplatesData] = useState();

  useEffect(() => {
    setTemplatesData(props.templateData);
  }, [props.templateData]);

  const getData = () => {
    if (props.templateName) {
      getTemplateLoaderData(props.templateName, props.data)
        .then((res) => {
          if (res.status === 200) {
            setIsLoaded(true);
            setTemplates(res.data.data);
            setRemoteTemplatesData(res.data.templatesData);
          }
        });
    }
  };

  useEffect(() => {
    setAllTemplatesData({ ...templatesData, ...remoteTemplatesData });
  }, [templatesData, remoteTemplatesData]);



  useEffect(() => {
    getData();
  }, [props.templateName]);

  if (!isLoaded || !allTemplatesData) {
    return <Fragment />;
  }

  return (
    <TemplateLoaderContext.Provider
      value={{
        data: allTemplatesData,
      }}
    >
      <TemplatesParser templates={templates} />
    </TemplateLoaderContext.Provider>
  );
}

const componentsMap: any = {
  "view.content": ViewContent,
  "view.formcontent": ViewFormContent,

  "list.content": ListContent,
  "list.toolbar": ListToolbar, // MOVED
  "list.toolbar.new-button": ListToolbarNewButton,// MOVED
  "list.toolbar.qs": ListToolbarQs,// MOVED
  "list.toolbar.sort": ListToolbarSort,// MOVED
  "list.toolbar.export": ListToolbarExport,// MOVED
  "list.toolbar.tabs-switch": ListToolbarTabsSwitch,// MOVED
  "list.toolbar.detailed-search": ListToolbarDetailedSearch,// MOVED
  "list.toolbar.filters": ListToolbarQuickFilters,// MOVED

  "edit.content": EditContent,
  "edit.formcontent": EditFormContent,

  "popup.window": PopupWindow,
  "maintoolbar.title": ToolbarTitle,

  "table.th": TableTh,
  "table.td": TableTd,
  "table.tr": TableTr,
  "table.table": Table,

  "data.string": DataString,

  "view.filewidget": ViewFilesWidget,
  "view.statuswidget": ViewStatusWidget,
  "view.statuswidgetwithactions": ViewStatusWidgetWithActions,

  "layout.flexrow": FlexRow,

  "widgets.numberwidget": NumberCardWidget,
  "widgets.WhiteCardWithViewFormWidget": WhiteCardWithViewFormWidget,

  "widgets.dfnumberwidget": NumberCardDfWidget,

  "toolbar.action-button": ToolbarActionButton,
  "edit.contentpopup": EditContentPopup,

  "widgets.freebgbadgewidget": FreeBgBadgeWidget,

  "form.rows.widerow": WideRow,
  "form.rows.compactrow": CompactRow,

  "form.editableform": EditableForm,
  "form.fieldlabel": FormFieldLabel,
  "form.fieldseparator": FormFieldSeparator,
  "form.fieldtagcloud": FormFieldTagCloudTemplate,
  "form.label": FormLabel,
  "form.hint": FormHint,
  "form.roform": RoForm,

  "form.editable.arrayfield": ArrayEditableField,
  "form.editable.arrayfield.toolbar.addButton": AddButton,

  "form.editable.audiofield": AudioEditableField,
  "form.editable.boolfield": BoolEditableField,
  "form.editable.colorfield": ColorEditableField,
  "form.editable.datefield": DateEditableField,
  "form.editable.datetimefield": DateTimeEditableField,
  "form.editable.enummultinumberfield": EnumMultiNumberEditableField,
  "form.editable.enummultitextfield": EnumMultiTextEditableField,
  "form.editable.enumnumberfield": EnumNumberEditableField,
  "form.editable.enumtextfield": EnumTextEditableField,
  "form.editable.filefield": FileEditableField,
  "form.editable.filemultiplefield": FileMultipleEditableField,
  "form.editable.floatfield": FloatEditableField,
  "form.editable.imagefield": ImageEditableField,
  "form.editable.largetextfield": LargeTextEditableField,
  "form.editable.numberfield": NumberEditableField,
  "form.editable.objectfield": ObjectEditableField,
  "form.editable.statusfield": StatusEditableField,
  "form.editable.stringarrayfield": StringArrayEditableField,
  "form.editable.stringfield": StringEditableField,

  "form.ro.arrayfield": ArrayRoField,
  "form.ro.audiofield": AudioRoField,
  "form.ro.boolfield": BoolRoField,
  "form.ro.colorfield": ColorRoField,
  "form.ro.datefield": DateRoField,
  "form.ro.datetimefield": DateTimeRoField,
  "form.ro.enummultinumberfield": EnumMultiNumberRoField,
  "form.ro.enummultitextfield": EnumMultiTextRoField,
  "form.ro.enumnumberfield": EnumNumberRoField,
  "form.ro.enumtextfield": EnumTextRoField,
  "form.ro.filemultiplefield": FileMultipleRoField,
  "form.ro.filefield": FileRoField,
  "form.ro.floatfield": FloatRoField,
  "form.ro.imagefield": ImageRoField,
  "form.ro.largetextfield": LargeTextRoField,
  "form.ro.numberfield": NumberRoField,
  "form.ro.objectfield": ObjectRoField,
  "form.ro.statusfield": StatusRoField,
  "form.ro.stringarrayfield": StringArrayRoField,
  "form.ro.stringfield": StringRoField,

  "form.dfro.arrayfield": ArrayDfRoField,
  "form.dfro.audiofield": AudioDfRoField,
  "form.dfro.boolfield": BoolDfRoField,
  "form.dfro.colorfield": ColorDfRoField,
  "form.dfro.datefield": DateDfRoField,
  "form.dfro.datetimefield": DateTimeDfRoField,
  "form.dfro.enummultinumberfield": EnumMultiNumberDfRoField,
  "form.dfro.enummultitextfield": EnumMultiTextDfRoField,
  "form.dfro.enumnumberfield": EnumNumberDfRoField,
  "form.dfro.enumtextfield": EnumTextDfRoField,
  "form.dfro.filemultiplefield": FileMultipleDfRoField,
  "form.dfro.filefield": FileDfRoField,
  "form.dfro.floatfield": FloatDfRoField,
  "form.dfro.imagefield": ImageDfRoField,
  "form.dfro.largetextfield": LargeTextDfRoField,
  "form.dfro.numberfield": NumberDfRoField,
  "form.dfro.objectfield": ObjectDfRoField,
  "form.dfro.statusfield": StatusDfRoField,
  "form.dfro.stringarrayfield": StringArrayDfRoField,
  "form.dfro.stringfield": StringDfRoField,

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

  "db.request.recordprovider": RequestRecordProvider,
  "db.request.recordprovider.inner": RequestRecordProviderInner,

  "cards.whitecard": WhiteCard,

  "view.pdf.container": ViewPdfWidget,
  "view.pdf.item": ViewPdfItem,

  "buttons.main-button": MainButton,
  "buttons.toolbar-button-with-menu": ToolbarButtonWithMenu,
  "buttons.rs-button": RsButton,
  "buttons.rs-button-template": RsButtonTemplate,
  'buttons.toolbar-button-element-with-action': ToolbarButtonElementWithAction,
  'buttons.toolbar-button-list-with-action': ToolbarButtonListWithAction,

  "modal.menu-item-with-create": MenuItemWithCreate,
  "modal.menu-item-with-edit": MenuItemWithEdit,

  'list.list-data-source': ListDataSource,
  'list.list-data-table': ListDataTable,
  'list.list-data-summary': ListDataSummary,
  'list.list-data-totals': ListDataTotals,

  'list.action.add-select-button': AddSelectButton,

  'tabs.TabContainer': TabContainer,

  'primitives.string': PrimitiveString,

  'content-widgets.MailsContent': MailsContent,
  'content-widgets.NotesContent': NotesContent,

  'main-menu.menu-title': MenuTitle,
  'main-menu.menu-folder': MenuFolder,
  'main-menu.menu-item': MenuItem,

  'toolbar.element-bookmark-button': ElementBookmarkButton,

  ...CustomEditComponentsMap,
  ...CustomListComponentsMap,
  ...PluginsMap,
};
interface TemplatesParserProps {
  templates: Template[];
  extraProps?: any;
}

export const TemplatesParser = (props: TemplatesParserProps) => {
  const { templates } = props;

  return (
    <Fragment>
      {templates.map((t: Template, tIndex: number) => {
        let Comp = Fragment;
        if (t.comp in componentsMap) {
          Comp = componentsMap[t.comp];
        } else {
          return <div>NO TEMPLATE {t.comp}</div>;
        }
        return <Comp {...t.props} {...props.extraProps} key={`t-${tIndex}`} />;
      })}
    </Fragment>
  );
};
