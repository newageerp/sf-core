import { UIConfig } from "@newageerp/nae-react-ui";
import { INaeTab, INaeTabField } from "@newageerp/nae-react-ui/dist/interfaces";

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
