import React from "react";
import { useHistory, useParams } from "@newageerp/v3.templates.templates-core";
import {TemplatesLoader} from '@newageerp/v3.templates.templates-core';

interface ParamTypes {
  schema: string;
  type: string;
  id: string;
}

interface Props {
  isPopup?: boolean,

  schema?: string,
  type?: string,
  id?: string,

  onBack?: () => void,
}

export default function MainEdit(props: Props) {
  const history = useHistory();
  const routeParams = useParams<ParamTypes>();

  const commonProps = { ...routeParams, ...props };

  return <TemplatesLoader
    key={`${commonProps.schema}-${commonProps.type}-${commonProps.id}`}
    templateName="PageMainEdit"
    data={commonProps}
    templateData={{
      onBack: () => {
        props.onBack ? props.onBack() : history.goBack()
      },
    }}
  />;
}
