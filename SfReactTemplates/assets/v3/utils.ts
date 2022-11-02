import { UIConfig } from "@newageerp/nae-react-ui";
import { INaeSchema, INaeTab, INaeTabField } from "@newageerp/nae-react-ui/dist/interfaces";
import { NaeSSchema } from "../../config/NaeSSchema";

export const filterScopes = (
  element: any,
  userState: any,
  scopesToCheck: any,
) => {
  if (!element) {
    return true;
  }
  if (!scopesToCheck) {
    return true;
  }
  let isShow = true;
  const hideScopes = scopesToCheck.hideScopes ? scopesToCheck.hideScopes : [];
  const showScopes = scopesToCheck.showScopes ? scopesToCheck.showScopes : [];
  const elementScopes = [
    ...(element.scopes ? element.scopes : []),
    ...userState.scopes,
  ];

  if (showScopes.length > 0) {
    const intersections = elementScopes.filter(function (n: string) {
      return showScopes.indexOf(n) !== -1;
    });
    isShow = intersections.length > 0;
    // console.log({ intersections });
  }
  if (hideScopes.length > 0) {
    const intersections = elementScopes.filter(function (n: string) {
      return hideScopes.indexOf(n) !== -1;
    });

    if (intersections.length > 0) {
      isShow = false;
    }
  }
  return isShow;
};

export const getTabFromSchemaAndType = (
  schema: string,
  type: string,
): INaeTab => {
  const tabs = UIConfig.tabs();
  const founded = tabs.filter(
    (tab) => tab.schema === schema && tab.type === type,
  );
  if (founded.length > 0) {
    return founded[0];
  }
  return {
    fields: [],
    schema: schema,
    type: type,
    sort: [],
  };
};

export const getTabFieldsToReturn = (tab: INaeTab | null) => {
  let fieldsToReturn: string[] = ["scopes", "_viewTitle"];
  if (!!tab && tab.fields) {
    tab.fields.forEach((f: INaeTabField) => {
      fieldsToReturn.push(f.relName ? f.key + "." + f.relName : f.key);
      if (f.custom && f.custom.fieldsToReturn) {
        fieldsToReturn = [...fieldsToReturn, ...f.custom.fieldsToReturn];
      }
    });
  } else {
    fieldsToReturn.push("id");
  }
  return fieldsToReturn;
};

export const getKeysFromObject = (defObject: any, prefix = '') => {
  let makeKeys: string[] = []
  Object.keys(defObject).forEach((key) => {
    // @ts-ignore
    const val = defObject[key]
    if (Array.isArray(val) && val.length > 0) {
      let makeKeysAr: string[] = []

      Object.keys(val[0]).forEach((keyArr) => {
        // @ts-ignore
        const valRel = val[0][keyArr]

        if (typeof valRel === 'object' && valRel) {
          const _keys = getKeysFromObject(valRel, prefix + keyArr + '.')
          makeKeysAr = [...makeKeysAr, ..._keys]
        } else {
          makeKeysAr.push(keyArr)
        }
      })
      makeKeys.push(key + ':' + makeKeysAr.join(','))
    } else if (typeof val === 'object' && val) {
      const _keys = getKeysFromObject(val, prefix + key + '.')

      makeKeys = [...makeKeys, ..._keys]
    } else {
      makeKeys.push(prefix + key)
    }
  })
  return makeKeys
}

export const getSchemaTitle = (_schema: string, plural: boolean) => {
  const schemas = NaeSSchema;

  let title: string = _schema
  schemas.forEach((s: INaeSchema) => {
    if (s.schema === _schema) {
      if (plural) {
        title = s.titlePlural
      } else {
        title = s.title
      }
    }
  })
  return title
}

export interface INaeWidget {
  schema: string
  type: WidgetType | string
  comp: any
  options: any
  sort: number
  hideScopes?: string[]
  showScopes?: string[]
}
export interface WidgetProps {
  type: WidgetType
  schema?: string
  element: any
  saveError?: any
  userState?: any
  extraOptions?: any
}
export interface ContentWidgetProps {
  schema: string
  element: any
  options: any
  userState?: any
  type?: WidgetType
  saveError?: any
}

export enum WidgetType {
  viewMainTop = 'viewMainTop',

  viewMainTop2LineBefore = 'viewMainTop2LineBefore',
  viewMainTop2LineAfter = 'viewMainTop2LineAfter',

  viewMainTop1LineBefore = 'viewMainTop1LineBefore',
  viewMainTop1LineAfter = 'viewMainTop1LineAfter',

  editRight = 'editRight',
  viewBottom = 'mainBottom',
  viewMiddle = 'mainMiddle',
  viewRightTop = 'mainRightTop',
  viewRight = 'mainRight',
  viewExtraBottom = 'viewExtraBottom',
  viewRightButtons = 'mainRightButtons',
  viewAfterPdfButton = 'mainAfterPdfButton',
  viewAfterConvertButton = 'mainAfterConvertButton',
  viewAfterCreateButton = 'viewAfterCreateButton',
  viewAfterEditButton = 'mainAfterEditButton',
  skip = 'skip',
  listAfterTable = 'listAfterTable',
}