import React, { useEffect, useState } from "react";
import { useNaeRecord } from "../old-ui/OldNaeRecord";
import { Template, useTemplateLoader } from "../templates/TemplateLoader";
import ViewContentChild from "./ViewContentChild";

interface Props {
  schema: string;
  type: string;
  id: string;

  formContent: Template[];
  editable: boolean;
  removable: boolean;

  rightContent: Template[];
  middleContent: Template[];
  bottomContent: Template[];
  bottomExtraContent: Template[];

  afterTitleBlockContent: Template[];
  elementToolbarAfterFieldsContent: Template[];
  elementToolbarLine2BeforeContent: Template[];
  elementToolbarMoreMenuContent: Template[];
}

export default function ViewContent(props: Props) {
  const { data: tdata } = useTemplateLoader();
  // const { element } = useNaeRecord();
  // const [oldScopes, setOldScopes] = useState(element ? element.scopes : undefined);
  // useEffect(() => {
  //   if (tdata.onElementScopeChange) {
  //     if (element) {
  //       if (oldScopes === undefined) {
  //         setOldScopes(element.scopes);
  //       } else {
  //         if (JSON.stringify(element.scopes) !== JSON.stringify(oldScopes)) {
  //           tdata.onElementScopeChange();
  //         }
  //       }
  //     }
  //   }
  // }, [element]);

  return (
    <ViewContentChild
      schema={props.schema}
      type={props.type}
      id={props.id}
      onBack={tdata.onBack}
      formContent={props.formContent}
      editable={props.editable}
      removable={props.removable}
      rightContent={props.rightContent}
      middleContent={props.middleContent}
      bottomContent={props.bottomContent}
      bottomExtraContent={props.bottomExtraContent}
      afterTitleBlockContent={props.afterTitleBlockContent}
      elementToolbarAfterFieldsContent={props.elementToolbarAfterFieldsContent}
      elementToolbarLine2BeforeContent={props.elementToolbarLine2BeforeContent}
      elementToolbarMoreMenuContent={props.elementToolbarMoreMenuContent}
    />
  );
}
