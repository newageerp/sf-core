import React from "react";
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
  bottomContent: Template[];

  afterTitleBlockContent: Template[];
  elementToolbarAfterFieldsContent: Template[];
  elementToolbarLine2BeforeContent: Template[];
  elementToolbarMoreMenuContent: Template[];
}

export default function ViewContent(props: Props) {
  const { data: tdata } = useTemplateLoader();

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
      bottomContent={props.bottomContent}
      afterTitleBlockContent={props.afterTitleBlockContent}
      elementToolbarAfterFieldsContent={props.elementToolbarAfterFieldsContent}
      elementToolbarLine2BeforeContent={props.elementToolbarLine2BeforeContent}
      elementToolbarMoreMenuContent={props.elementToolbarMoreMenuContent}
    />
  );
}
