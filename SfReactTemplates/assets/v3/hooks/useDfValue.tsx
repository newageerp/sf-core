import { Fragment } from "react";
import {
  getHookForSchema,
  useEmptyHook,
} from "../../_custom/models-cache-data/ModelFields";
import StatusDfRoField from "../form/df-ro-fields/StatusDfRoField";
import FileDfRoField from "../form/df-ro-fields/FileDfRoField";
import FileMultipleDfRoField from "../form/df-ro-fields/FileMultipleDfRoField";
import ColorDfRoField from '../form/df-ro-fields/ColorDfRoField';
import ImageDfRoField from '../form/df-ro-fields/ImageDfRoField';
import AudioDfRoField from '../form/df-ro-fields/AudioDfRoField';
import ObjectDfRoField from '../form/df-ro-fields/ObjectDfRoField';
import StringArrayDfRoField from '../form/df-ro-fields/StringArrayDfRoField';
import FloatDfRoField from "../form/df-ro-fields/FloatDfRoField";
import NumberDfRoField from '../form/df-ro-fields/NumberDfRoField';
import DateDfRoField from '../form/df-ro-fields/DateDfRoField';
import DateTimeDfRoField from '../form/df-ro-fields/DateTimeDfRoField';
import BoolDfRoField from '../form/df-ro-fields/BoolDfRoField';
import LargeTextDfRoField from "../form/df-ro-fields/LargeTextDfRoField";
import EnumMultiNumberDfRoField from '../form/df-ro-fields/EnumMultiNumberDfRoField';
import EnumMultiTextDfRoField from '../form/df-ro-fields/EnumMultiTextDfRoField';
import EnumNumberDfRoField from '../form/df-ro-fields/EnumNumberDfRoField';
import EnumTextDfRoField from '../form/df-ro-fields/EnumTextDfRoField';
import ArrayDfRoField from "../form/df-ro-fields/ArrayDfRoField";
import StringDfRoField from '../form/df-ro-fields/StringDfRoField';
import { RsButton as RsButtonTpl } from "@newageerp/v3.buttons.rs-button";
import { getPropertyForPath } from "../utils";

interface Props {
  id: number;
  path: string;
}

export function useDfValue(props: Props) {
  let depth = 1;
  const pathArray = props.path.split(".");
  let hook1 = getHookForSchema(pathArray[0]);
  let hook2 = useEmptyHook;
  let hook3 = useEmptyHook;

  if (pathArray.length === 3) {
    const prop = getPropertyForPath(props.path);
    if (prop) {
      hook2 = getHookForSchema(prop.schema);
      depth = 2;
    }
  }

  if (pathArray.length === 4) {
    const prop = getPropertyForPath(props.path);
    const prop2 = getPropertyForPath(`${pathArray[0]}.${pathArray[1]}.${pathArray[2]}`);

    if (prop && prop2) {
      hook2 = getHookForSchema(prop2.schema);
      hook3 = getHookForSchema(prop.schema);

      depth = 3;
    }
  }

  const element: any = hook1(props.id);
  const element2: any = hook2(
    depth > 1 && element && element[pathArray[1]]
      ? element[pathArray[1]].id
      : -1
  );
  const element3 = hook3(
    depth > 2 && element2 && element2[pathArray[2]]
      ? element2[pathArray[2]].id
      : -1
  );

  if (depth === 3) {
    console.log({
      element,
      element2,
      element3,
    })
  }

  if (depth === 3) {
    if (!element3) {
      return undefined;
    }
    return element3[pathArray[3]];
  }

  if (depth === 2) {
    if (!!element && !!element[pathArray[1]] && !!element[pathArray[1]][pathArray[2]]) {
      return element[pathArray[1]][pathArray[2]];
    }

    if (!element2) {
      return undefined;
    }
    return element2[pathArray[2]];
  }
  if (depth === 1) {
    if (!element) {
      return undefined;
    }
    return element[pathArray[1]];
  }
  return undefined;
}

interface DfValueViewProps extends Props {
  link?: boolean,
  extra?: any,
}

export function DfValueView(props: DfValueViewProps) {

  let component = <Fragment />
  const property = getPropertyForPath(props.path);
  if (property?.naeType === 'status') {
    component = <StatusDfRoField id={props.id} schema={property.schema} fieldKey={props.path} />
  }
  if (property?.naeType === 'file') {
    component = <FileDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'fileMultiple') {
    component = <FileMultipleDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'color') {
    component = <ColorDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'image') {
    component = <ImageDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'audio') {
    component = <AudioDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'object') {
    component = <ObjectDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'string_array') {
    component = <StringArrayDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'float') {
    component = <FloatDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'number') {
    component = <NumberDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'date') {
    component = <DateDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'datetime') {
    component = <DateTimeDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'bool') {
    component = <BoolDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'text') {
    component = <LargeTextDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'enum_multi_number') {
    component = <EnumMultiNumberDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'enum_multi_text') {
    component = <EnumMultiTextDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'enum_text') {
    component = <EnumTextDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'enum_number') {
    component = <EnumNumberDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'array') {
    component = <ArrayDfRoField id={props.id} fieldKey={props.path} />
  }
  if (property?.naeType === 'string') {
    component = <StringDfRoField id={props.id} fieldKey={props.path} {...props.extra} />
  }
  if (!component) {
    component = <div>DF NO TEMPLATE {props.path}:{props.id}</div>;
  }

  if (props.link) {
    return (
      <RsButtonTpl
        defaultClick={'modal'}
        id={props.id}
        schema={property ? property.schema : ''}
        button={{
          children: component,
          color: "white",
          skipPadding: true,
        }}
      />
    )
  }
  return component;
}